<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use App\Support\SalesCrm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SalesCrmEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    /** @return list<array{string}> */
    public static function statuses(): array
    {
        return [['duplicate'], ['converted'], ['voicemail'], ['interested'], ['not_interested'], ['opportunity'], ['new']];
    }

    #[DataProvider('statuses')]
    public function test_sales_statuses_can_be_saved_and_filtered_for_leads_and_contacts(string $status): void
    {
        $salesUser = User::factory()->sales()->create();
        $lead = Lead::factory()->create(['name' => 'Selected prospect']);
        $contact = Contact::factory()->create(['name' => 'Selected contact']);
        $this->actingAs($salesUser)->put(route('sales.leads.update', $lead), ['email' => $lead->email, 'status' => $status])->assertSessionHasNoErrors();
        $this->put(route('sales.contacts.update', $contact), ['name' => $contact->name, 'status' => $status])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'status' => $status]);
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'status' => $status]);
        $this->get(route('sales.dashboard', ['status' => $status]))->assertOk()->assertSee('Selected prospect')->assertSee(SalesCrm::statusLabel($status));
        $this->get(route('sales.contacts', ['status' => $status]))->assertOk()->assertSee('Selected contact');
    }

    public function test_phone_numbers_are_masked_in_lists_and_edit_forms_and_blank_edits_keep_them(): void
    {
        $salesUser = User::factory()->sales()->create();
        $lead = Lead::factory()->create(['phone' => '+61 400 123 456']);
        $contact = Contact::factory()->create(['phone' => '+61 400 123 456']);
        $this->actingAs($salesUser);
        foreach (['/sales', '/sales/leads/'.$lead->id, '/sales/contacts', '/sales/contacts/'.$contact->id.'/edit'] as $path) {
            $this->get($path)->assertOk()->assertSee('61*******56')->assertDontSee('+61 400 123 456', false)->assertDontSee('61400123456', false);
        }
        $this->put(route('sales.leads.update', $lead), ['email' => $lead->email, 'phone' => '', 'status' => 'interested'])->assertSessionHasNoErrors();
        $this->put(route('sales.contacts.update', $contact), ['name' => $contact->name, 'phone' => '', 'status' => 'active'])->assertSessionHasNoErrors();
        $this->assertSame('+61 400 123 456', $lead->fresh()->phone);
        $this->assertSame('+61 400 123 456', $contact->fresh()->phone);
        $this->get(route('sales.leads.dial', $lead))->assertRedirect('tel:+61400123456');
        $contact->update(['status' => 'dnd']);
        $this->get(route('sales.contacts.dial', $contact))->assertForbidden();
        $this->assertSame('**', SalesCrm::maskPhone('12'));
    }

    public function test_csv_import_creates_owned_contacts_and_skips_duplicate_emails_and_formatted_phones(): void
    {
        $salesUser = User::factory()->sales()->create();
        Contact::factory()->create(['email' => 'existing@example.com', 'phone' => '+61 400 111 222']);
        $csv = "name,email,phone,company,status,notes\nAlex,alex@example.com,0400111222,Example,interested,Requested research\nDuplicate,EXISTING@example.com,,,new,\nSame phone,other@example.com,+61400111222,,new,\nRepeated,alex@example.com,,,new,\nNew contact,new@example.com,0400333444,,,\n";
        $this->actingAs($salesUser)->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $csv)])
            ->assertSessionHasNoErrors()->assertRedirect(route('sales.contacts'));
        $this->assertDatabaseCount('contacts', 3);
        $this->assertDatabaseHas('contacts', ['email' => 'alex@example.com', 'status' => 'interested', 'owner_id' => $salesUser->id]);
        $this->assertDatabaseHas('contacts', ['email' => 'new@example.com', 'status' => 'new']);
        $this->get(route('sales.contacts'))->assertOk()->assertSee('Download sample CSV');
        $this->get(route('sales.contacts.sample'))->assertOk()->assertDownload('contacts-sample.csv');
        $sample = $this->get(route('sales.contacts.sample'))->streamedContent();
        $this->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('sample.csv', $sample)])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contacts', ['email' => 'alex@example.com']);
    }

    public function test_invalid_import_rejects_the_whole_file(): void
    {
        $salesUser = User::factory()->sales()->create();
        $csv = "name,email,status\nAlex,alex@example.com,new\nInvalid,not-an-email,new\n";
        $this->actingAs($salesUser)->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $csv)])
            ->assertSessionHasErrors('contact_csv');
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_import_sample_and_dial_routes_require_sales_access(): void
    {
        $contact = Contact::factory()->create();
        $this->get(route('sales.contacts.sample'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create());
        $this->get(route('sales.contacts.sample'))->assertForbidden();
        $this->post(route('sales.contacts.import'))->assertForbidden();
        $this->get(route('sales.contacts.dial', $contact))->assertForbidden();
    }

    public function test_malformed_headers_and_unknown_statuses_are_rejected(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        foreach (["name,,email\nAlex,,alex@example.com\n", "name,status\nAlex,unrecognised\n", "name,email\n"] as $csv) {
            $this->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $csv)])
                ->assertSessionHasErrors('contact_csv');
        }
        $this->assertDatabaseCount('contacts', 0);
    }
}
