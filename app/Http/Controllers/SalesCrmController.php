<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesCrmController extends Controller
{
    public function dashboard(Request $request): View
    {
        $request->validate(['q' => 'nullable|string|max:100', 'status' => ['nullable', Rule::in($this->leadStatuses())]]);
        $leads = Lead::query()->with(['assignee', 'contact'])->when($request->filled('q'), function ($query) use ($request): void {
            $term = $request->string('q')->toString();
            $query->where(fn ($query) => $query->where('name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->orWhere('phone', 'like', '%'.$term.'%'));
        })->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))->latest()->paginate(15)->withQueryString();
        $leads->getCollection()->each(function (Lead $lead): void {
            if ($lead->contact && $lead->contact->status !== 'active') {
                $lead->setAttribute('phone', null);
            }
        });

        $metrics = [
            'new' => Lead::where('status', 'new')->count(),
            'active' => Lead::whereIn('status', ['contacted', 'qualified', 'proposal'])->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'contacts' => Contact::where('status', 'active')->count(),
        ];

        return view('sales.dashboard', ['leads' => $leads, 'metrics' => $metrics, 'statuses' => $this->leadStatuses()]);
    }

    public function showLead(Lead $lead): View
    {
        $lead->load(['assignee', 'contact', 'activities.user']);
        if ($lead->contact && $lead->contact->status !== 'active') {
            $lead->setAttribute('phone', null);
        }

        return view('sales.lead', ['lead' => $lead, 'salesUsers' => User::where(fn ($query) => $query->where('is_sales', true)->orWhere('is_admin', true))->where('is_active', true)->orderBy('name')->get(), 'statuses' => $this->leadStatuses()]);
    }

    public function updateLead(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:120',
            'email' => 'required|email|max:200',
            'phone' => 'nullable|string|max:30',
            'status' => ['required', Rule::in($this->leadStatuses())],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where(fn ($query) => $query->where('is_sales', true)->orWhere('is_admin', true))],
        ]);
        $previousStatus = $lead->status;
        if ($lead->contact()->where('status', '!=', 'active')->exists()) {
            unset($data['phone']);
        }
        $lead->update($data);
        if ($previousStatus !== $lead->status) {
            $this->recordActivity($request, $lead, 'status', $lead->status, "Status changed from {$previousStatus} to {$lead->status}.");
        }

        return back()->with('success', 'Lead details updated.');
    }

    public function storeActivity(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['response', 'note', 'email', 'sms', 'meeting'])],
            'details' => 'required|string|max:5000',
            'outcome' => 'nullable|string|max:100',
        ]);
        $this->recordActivity($request, $lead, $data['type'], $data['outcome'] ?? null, $data['details']);

        return back()->with('success', 'Lead journey updated.');
    }

    public function logCall(Request $request, Lead $lead): RedirectResponse
    {
        abort_if(! $lead->phone, 422, 'This lead has no phone number.');
        abort_if($lead->contact && $lead->contact->status !== 'active', 403, 'This contact cannot be called.');
        $data = $request->validate([
            'outcome' => ['required', Rule::in(['answered', 'voicemail', 'no_answer', 'busy', 'wrong_number'])],
            'duration_seconds' => 'nullable|integer|min:0|max:86400',
            'details' => 'nullable|string|max:5000',
        ]);
        LeadActivity::create($data + ['lead_id' => $lead->id, 'user_id' => $request->user()->id, 'type' => 'call', 'occurred_at' => now()]);
        if ($lead->status === 'new') {
            $lead->update(['status' => 'contacted']);
        }

        return back()->with('success', 'Call outcome recorded.');
    }

    public function convert(Request $request, Lead $lead): RedirectResponse
    {
        $contact = DB::transaction(function () use ($request, $lead): Contact {
            $lockedLead = Lead::query()->lockForUpdate()->findOrFail($lead->id);
            if ($lockedLead->contact_id) {
                return Contact::findOrFail($lockedLead->contact_id);
            }
            $contact = Contact::create([
                'owner_id' => $lockedLead->assigned_to ?? $request->user()->id,
                'source_lead_id' => $lockedLead->id,
                'name' => $lockedLead->name ?: $lockedLead->email,
                'email' => $lockedLead->email,
                'phone' => $lockedLead->phone,
                'status' => 'active',
                'converted_at' => now(),
            ]);
            $lockedLead->update(['status' => 'converted', 'contact_id' => $contact->id]);
            $this->recordActivity($request, $lockedLead, 'converted', 'customer', 'Lead converted to a customer contact.');

            return $contact;
        });

        return redirect()->route('sales.contacts.edit', $contact)->with('success', 'Lead converted to a customer.');
    }

    public function contacts(Request $request): View
    {
        $request->validate(['q' => 'nullable|string|max:100', 'status' => ['nullable', Rule::in($this->contactStatuses())]]);
        $contacts = Contact::query()->with('owner')->when($request->filled('q'), function ($query) use ($request): void {
            $term = $request->string('q')->toString();
            $query->where(fn ($query) => $query->where('name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->orWhere('phone', 'like', '%'.$term.'%')->orWhere('company', 'like', '%'.$term.'%'));
        })->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))->latest()->paginate(15)->withQueryString();

        return view('sales.contacts', ['contacts' => $contacts, 'statuses' => $this->contactStatuses()]);
    }

    public function createContact(): View
    {
        return view('sales.contact', ['contact' => new Contact, 'statuses' => $this->contactStatuses()]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $contact = Contact::create($this->contactData($request) + ['owner_id' => $request->user()->id]);

        return redirect()->route('sales.contacts.edit', $contact)->with('success', 'Contact added.');
    }

    public function editContact(Contact $contact): View
    {
        return view('sales.contact', ['contact' => $contact, 'statuses' => $this->contactStatuses()]);
    }

    public function updateContact(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update($this->contactData($request));

        return back()->with('success', 'Contact updated.');
    }

    public function destroyContact(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('sales.contacts')->with('success', 'Contact removed.');
    }

    /** @return array<string, mixed> */
    private function contactData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:150',
            'status' => ['required', Rule::in($this->contactStatuses())],
            'notes' => 'nullable|string|max:5000',
        ]);
    }

    private function recordActivity(Request $request, Lead $lead, string $type, ?string $outcome, ?string $details): void
    {
        LeadActivity::create(['lead_id' => $lead->id, 'user_id' => $request->user()->id, 'type' => $type, 'outcome' => $outcome, 'details' => $details, 'occurred_at' => now()]);
    }

    /** @return list<string> */
    private function leadStatuses(): array
    {
        return ['new', 'contacted', 'qualified', 'proposal', 'converted', 'lost'];
    }

    /** @return list<string> */
    private function contactStatuses(): array
    {
        return ['active', 'blocked', 'dnd', 'archived'];
    }
}
