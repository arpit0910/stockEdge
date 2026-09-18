<?php

namespace App\Http\Controllers;

use App\Support\AdminResources;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminWorkspaceController extends Controller
{
    public function overview(): View
    {
        $metrics = [
            'published' => DB::table('reports')->where('published', true)->count(),
            'drafts' => DB::table('reports')->where('published', false)->count() + DB::table('articles')->where('published', false)->count(),
            'members' => DB::table('users')->where('is_active', true)->count(),
            'leads' => DB::table('leads')->where('status', 'new')->count(),
        ];
        $coverage = DB::table('stocks')->select('sector', DB::raw('count(*) as total'))->groupBy('sector')->orderByDesc('total')->get();
        $activity = DB::table('admin_activities')->latest('id')->limit(6)->get();
        $queue = DB::table('reports')->where('published', false)->latest()->limit(5)->get();
        $recentReports = DB::table('reports')->latest()->limit(6)->get();
        $sections = DB::table('site_sections')->orderBy('position')->orderBy('id')->get();
        $newLeads = DB::table('leads')->where('status', 'new')->latest()->limit(4)->get();
        $pending = DB::table('subscription_requests')->where('status', 'pending')->count();

        return view('admin.overview', compact('metrics', 'coverage', 'activity', 'queue', 'recentReports', 'sections', 'newLeads', 'pending'));
    }

    public function index(Request $request, string $resource): View
    {
        $definition = AdminResources::definition($resource);
        $request->validate(['q' => 'nullable|string|max:100', 'status' => 'nullable|string|max:40', 'sort' => ['nullable', Rule::in(['updated', 'oldest', 'name'])]]);
        $query = DB::table($definition['table']);
        if ($request->filled('q')) {
            $term = $request->string('q')->toString();
            $query->where(function ($q) use ($definition, $term): void {
                foreach ($definition['search'] as $field) {
                    $q->orWhere($field, 'like', '%'.$term.'%');
                }
            });
        }
        if ($request->filled('status') && $definition['status']) {
            $query->where($definition['status'], $request->input('status'));
        }
        $counts = ['total' => DB::table($definition['table'])->count()];
        if ($definition['status'] === 'published') {
            $counts['published'] = DB::table($definition['table'])->where('published', true)->count();
            $counts['drafts'] = $counts['total'] - $counts['published'];
        }
        if ($resource === 'sections' || $resource === 'taxonomies' || $resource === 'plans') {
            $query->orderBy('position')->orderBy('id');
        } else {
            match ($request->input('sort', 'updated')) {
                'oldest' => $query->orderBy('created_at'),'name' => $query->orderBy(array_key_first($definition['columns'])),default => $query->orderByDesc('updated_at')->orderByDesc('id')
            };
        }
        $records = $query->paginate(15)->withQueryString();

        return view('admin.index', compact('definition', 'records', 'counts', 'resource'));
    }

    public function edit(string $resource, ?int $id = null): View
    {
        $definition = AdminResources::definition($resource);
        abort_if(! $id && ! $definition['create'], 404);
        $record = $id ? DB::table($definition['table'])->find($id) : null;
        abort_if($id && ! $record, 404);
        $history = $id ? DB::table('admin_activities')->where('resource', $resource)->where('record_id', $id)->latest('id')->limit(10)->get() : collect();
        $member = ($record && $resource === 'requests') ? DB::table('users')->find($record->user_id) : null;

        return view('admin.edit', compact('resource', 'definition', 'record', 'history', 'member'));
    }

    public function save(Request $request, string $resource, ?int $id = null): RedirectResponse
    {
        $definition = AdminResources::definition($resource);
        abort_if(! $id && ! $definition['create'], 403);
        $record = $id ? DB::table($definition['table'])->find($id) : null;
        abort_if($id && ! $record, 404);
        $rules = [];
        foreach ($definition['fields'] as $field => $spec) {
            $rules[$field] = $spec['rules'];
            if ($spec['type'] === 'checkbox') {
                $request->merge([$field => $request->boolean($field)]);
            }
        }
        foreach (['slug', 'symbol', 'email'] as $unique) {
            if (isset($rules[$unique])) {
                $rules[$unique] = is_array($rules[$unique]) ? $rules[$unique] : explode('|', $rules[$unique]);
                $rules[$unique][] = Rule::unique($definition['table'], $unique)->ignore($id);
            }
        }
        if ($resource === 'plans') {
            $rules['name'] = [...explode('|', $rules['name']), Rule::unique('plans', 'name')->ignore($id)];
        }
        if ($resource === 'taxonomies') {
            $rules['name'] = [...explode('|', $rules['name']), Rule::unique('taxonomies', 'name')->where('kind', $request->input('kind'))->ignore($id)];
        }
        if ($resource === 'members' && ! $id) {
            $rules['password'] = 'required|string|min:12|max:200';
        }
        if ($id) {
            $rules['_version'] = 'required|string';
        }
        $data = $request->validate($rules);
        $version = $data['_version'] ?? null;
        unset($data['_version']);
        if ($resource === 'pages') {
            if ($record?->system && $record->slug !== $data['slug']) {
                throw ValidationException::withMessages(['slug' => 'The address of a system page cannot be changed.']);
            }
            $reserved = ['admin', 'dashboard', 'account', 'research', 'reports', 'stocks', 'insights', 'login', 'register', 'logout', 'forgot-password', 'reset-password', 'enquiries', 'sample-report', 'holdings', 'watchlist', 'subscribe', 'up', 'storage', 'css', 'js', 'images', 'public', 'api'];
            if (in_array(strtolower($data['slug']), $reserved)) {
                throw ValidationException::withMessages(['slug' => 'This address is reserved for an application route.']);
            }
        }
        if ($resource === 'sections') {
            if ((bool) ($data['button_label'] ?? null) !== (bool) ($data['button_url'] ?? null)) {
                throw ValidationException::withMessages(['button_url' => 'Provide both button text and destination, or leave both empty.']);
            }
        }
        if (in_array($resource, ['articles', 'sections']) && ! empty($data['image_path']) && empty($data['image_alt'])) {
            throw ValidationException::withMessages(['image_alt' => 'Add a description for the selected image.']);
        }
        if ($resource === 'plans' && $data['is_trial'] && ((float) $data['monthly_price'] !== 0.0 || (float) $data['yearly_price'] !== 0.0)) {
            throw ValidationException::withMessages(['monthly_price' => 'A free trial plan must have zero monthly and annual prices.']);
        }
        if ($resource === 'taxonomies' && $record && $data['kind'] !== $record->kind) {
            throw ValidationException::withMessages(['kind' => 'An existing classification cannot change type. Create a new classification instead.']);
        }
        if ($resource === 'members') {
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
                $data['remember_token'] = Str::random(60);
            }
            if ($data['trial_ends_at']) {
                $data['trial_ends_at'] = Carbon::parse($data['trial_ends_at'])->format('Y-m-d H:i:s');
            }
        }
        if ($resource === 'stocks') {
            $data['symbol'] = strtoupper($data['symbol']);
        }
        DB::transaction(function () use ($request, $resource, $definition, $id, $data, $version): void {
            $current = $id ? DB::table($definition['table'])->where('id', $id)->lockForUpdate()->first() : null;
            if ($id) {
                abort_unless($current, 404);
                $this->assertVersion($current, $version);
            }
            if ($resource === 'members' && $current && (! $data['is_admin'] || ! $data['is_active'])) {
                if ((int) $current->id === $request->user()->id) {
                    throw ValidationException::withMessages(['is_admin' => 'You cannot remove your own administrator access or suspend your own account.']);
                }
                if ($current->is_admin && $current->is_active && DB::table('users')->where('is_admin', true)->where('is_active', true)->lockForUpdate()->count() <= 1) {
                    throw ValidationException::withMessages(['is_admin' => 'Keep at least one active administrator.']);
                }
            }
            if ($resource === 'taxonomies' && $current && $current->name !== $data['name']) {
                [$table,$field] = match ($current->kind) {
                    'category' => ['reports', 'category'],'sector' => ['stocks', 'sector'],'topic' => ['articles', 'topic']
                };
                DB::table($table)->where($field, $current->name)->update([$field => $data['name'], 'updated_at' => $this->timestamp()]);
            }
            if ($resource === 'plans' && $current && $current->name !== $data['name']) {
                DB::table('subscription_requests')->where('plan', $current->name)->update(['plan' => $data['name'], 'updated_at' => $this->timestamp()]);
            }
            $values = $data + ['updated_at' => $this->timestamp()];
            if ($id) {
                DB::table($definition['table'])->where('id', $id)->update($values);
                $savedId = $id;
            } else {
                $savedId = DB::table($definition['table'])->insertGetId($values + ['created_at' => $this->timestamp()]);
            }
            if ($resource === 'members' && $id && (isset($data['password']) || ! $data['is_active'])) {
                DB::table('sessions')->where('user_id', $id)->delete();
            }
            $changed = array_keys(array_filter($data, fn ($value, $key) => ! $current || (string) ($current->$key ?? '') !== (string) $value, ARRAY_FILTER_USE_BOTH));
            $this->audit($request, $id ? 'updated' : 'created', $resource, $savedId, (string) ($data['title'] ?? $data['name'] ?? $data['email'] ?? AdminResources::label($current ?? (object) ['id' => $savedId])), $changed);
        });

        return redirect()->route('admin.records', $resource)->with('success', $definition['singular'].' saved. Changes are available immediately.');
    }

    public function delete(Request $request, string $resource, int $id): RedirectResponse
    {
        $definition = AdminResources::definition($resource);
        abort_unless($definition['delete'], 403);
        $request->validate(['_version' => 'required|string']);
        DB::transaction(function () use ($request, $resource, $id, $definition): void {
            $row = DB::table($definition['table'])->where('id', $id)->lockForUpdate()->first();
            abort_unless($row, 404);
            $this->assertVersion($row, $request->input('_version'));
            if ($resource === 'stocks' && (DB::table('reports')->where('stock_id', $id)->exists() || DB::table('holdings')->where('stock_id', $id)->exists() || DB::table('watchlists')->where('stock_id', $id)->exists())) {
                throw ValidationException::withMessages(['delete' => 'This company is referenced by research or member records and cannot be deleted.']);
            }
            if ($resource === 'pages' && $row->system) {
                throw ValidationException::withMessages(['delete' => 'System pages cannot be deleted. Edit their content or publication status instead.']);
            }
            if ($resource === 'taxonomies') {
                [$table,$field] = match ($row->kind) {
                    'category' => ['reports', 'category'],'sector' => ['stocks', 'sector'],'topic' => ['articles', 'topic']
                };
                if (DB::table($table)->where($field, $row->name)->exists()) {
                    throw ValidationException::withMessages(['delete' => 'Reassign linked content before deleting this classification.']);
                }
            }
            if ($resource === 'plans' && DB::table('subscription_requests')->where('plan', $row->name)->exists()) {
                throw ValidationException::withMessages(['delete' => 'This plan has member requests. Unpublish it instead.']);
            }
            DB::table($definition['table'])->where('id', $id)->delete();
            $this->audit($request, 'deleted', $resource, $id, AdminResources::label($row), []);
        });

        return redirect()->route('admin.records', $resource)->with('success', $definition['singular'].' deleted.');
    }

    public function reorder(Request $request, int $id): RedirectResponse
    {
        $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]]);
        DB::transaction(function () use ($request, $id): void {
            $rows = DB::table('site_sections')->orderBy('position')->orderBy('id')->lockForUpdate()->get();
            $index = $rows->search(fn ($r) => (int) $r->id === $id);
            abort_if($index === false, 404);
            $target = $index + ($request->input('direction') === 'up' ? -1 : 1);
            if ($target < 0 || $target >= $rows->count()) {
                return;
            }
            $ids = $rows->pluck('id')->all();
            [$ids[$index],$ids[$target]] = [$ids[$target], $ids[$index]];
            foreach ($ids as $position => $sectionId) {
                DB::table('site_sections')->where('id', $sectionId)->update(['position' => ($position + 1) * 10, 'updated_at' => $this->timestamp()]);
            }
            $this->audit($request, 'reordered', 'sections', $id, $rows[$index]->name, ['position']);
        });

        return back()->with('success', 'Homepage order updated.');
    }

    public function settings(): View
    {
        return view('admin.settings', ['settings' => DB::table('site_settings')->pluck('value', 'key')->all()]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $data = $request->validate(['brand_name' => 'required|string|max:40', 'announcement' => 'required|string|max:180', 'footer_description' => 'required|string|max:300', 'newsletter_title' => 'required|string|max:100', 'newsletter_description' => 'required|string|max:300', 'contact_email' => 'nullable|email|max:150', 'contact_phone' => 'nullable|string|max:40', 'contact_address' => 'nullable|string|max:300', 'meta_description' => 'required|string|max:170', 'seo_title_suffix' => 'nullable|string|max:60', 'default_social_image' => ['nullable', 'string', 'max:250', 'regex:~^(https?://|/)[^\s]+$~'], 'social_facebook' => 'nullable|url:http,https|max:250', 'social_x' => 'nullable|url:http,https|max:250', 'social_linkedin' => 'nullable|url:http,https|max:250', 'social_youtube' => 'nullable|url:http,https|max:250']);
        DB::transaction(function () use ($request, $data): void {
            foreach ($data as $key => $value) {
                DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value, 'updated_at' => $this->timestamp(), 'created_at' => $this->timestamp()]);
            }
            $this->audit($request, 'updated', 'settings', null, 'Website settings', array_keys($data));
        });

        return back()->with('success', 'Website settings saved.');
    }

    public function activity(): View
    {
        return view('admin.activity', ['events' => DB::table('admin_activities')->latest('id')->paginate(25)]);
    }

    public function media(): View
    {
        return view('admin.media', ['assets' => DB::table('media_assets')->latest()->paginate(18)]);
    }

    public function upload(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:120', 'alt_text' => 'required|string|max:200', 'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:max_width=6000,max_height=6000']);
        $path = $request->file('file')->store('media', 'public');
        try {
            DB::transaction(function () use ($request, $data, $path): void {
                $id = DB::table('media_assets')->insertGetId(['name' => $data['name'], 'alt_text' => $data['alt_text'], 'path' => $path, 'size' => $request->file('file')->getSize(), 'mime' => $request->file('file')->getMimeType(), 'created_at' => $this->timestamp(), 'updated_at' => $this->timestamp()]);
                $this->audit($request, 'uploaded', 'media', $id, $data['name'], []);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        return back()->with('success', 'Image uploaded. You can now select it in an article or homepage section.');
    }

    public function updateMedia(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:120', 'alt_text' => 'required|string|max:200']);
        DB::transaction(function () use ($request, $id, $data): void {
            abort_unless(DB::table('media_assets')->where('id', $id)->exists(), 404);
            DB::table('media_assets')->where('id', $id)->update($data + ['updated_at' => $this->timestamp()]);
            $this->audit($request, 'updated', 'media', $id, $data['name'], array_keys($data));
        });

        return back()->with('success', 'Image details saved. Existing article descriptions remain independently editable.');
    }

    public function mediaFile(string $filename): BinaryFileResponse
    {
        abort_unless(preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|webp)$/D', $filename), 404);
        $asset = DB::table('media_assets')->where('path', 'media/'.$filename)->first();
        abort_unless($asset, 404);

        return response()->file(Storage::disk('public')->path($asset->path), ['Content-Type' => $asset->mime, 'X-Content-Type-Options' => 'nosniff']);
    }

    private function assertVersion(object $record, ?string $version): void
    {
        if ($version !== (string) $record->updated_at) {
            throw ValidationException::withMessages(['_version' => 'This record changed while you were editing. Reload the editor before saving. Your submitted values are preserved below.']);
        }
    }

    private function timestamp(): string
    {
        return now()->format('Y-m-d H:i:s.u');
    }

    private function audit(Request $request, string $action, string $resource, ?int $id, string $label, array $fields): void
    {
        DB::table('admin_activities')->insert(['user_id' => $request->user()?->id, 'actor' => $request->user()?->name ?? 'Admin', 'action' => $action, 'resource' => $resource, 'record_id' => $id, 'label' => Str::limit($label, 250, ''), 'changed_fields' => json_encode(array_values(array_diff($fields, ['password', 'remember_token']))), 'created_at' => $this->timestamp(), 'updated_at' => $this->timestamp()]);
    }
}
