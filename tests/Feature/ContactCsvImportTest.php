<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ContactCsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_csv_is_accepted_when_mime_detection_reports_excel_or_binary(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        foreach (['application/vnd.ms-excel', 'application/octet-stream'] as $index => $mimeType) {
            $csv = "First name,Last name,Email address,Phone number,Country/Region\nAlex,Example,alex{$index}@example.com,,Australia\n";
            $file = UploadedFile::fake()->createWithContent('contacts.csv', $csv)->mimeType($mimeType);
            $this->post(route('sales.contacts.import'), ['contact_csv' => $file])->assertSessionHasNoErrors();
            $this->assertDatabaseHas('contacts', ['email' => "alex{$index}@example.com", 'country_region' => 'Australia']);
        }
    }

    public function test_unsupported_extensions_invalid_content_and_oversized_files_are_rejected(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        foreach ([
            UploadedFile::fake()->createWithContent('contacts.xlsx', "name,email\nAlex,alex@example.com\n"),
            UploadedFile::fake()->createWithContent('contacts.csv', "PK\x03\x04\x00\x00binary workbook"),
            UploadedFile::fake()->createWithContent('contacts.csv', "name,email\nAlex,alex@example.com\n")->size(2049),
        ] as $file) {
            $this->post(route('sales.contacts.import'), ['contact_csv' => $file])->assertSessionHasErrors('contact_csv');
            $this->assertDatabaseCount('contacts', 0);
        }
    }

    public function test_crm_status_navigation_has_icons_on_dashboard_and_contacts(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        foreach (['sales.dashboard', 'sales.contacts'] as $routeName) {
            $response = $this->get(route($routeName))->assertOk();
            $document = new \DOMDocument;
            @$document->loadHTML($response->getContent());
            $xpath = new \DOMXPath($document);
            $links = $xpath->query('//div[contains(@class, "crm-status-links")]/a');
            $this->assertSame(14, $links->length);
            foreach ($links as $link) {
                $this->assertSame(1, $xpath->query('.//svg[@aria-hidden="true"]/path', $link)->length);
            }
        }
    }

    public function test_requested_columns_preserve_names_country_and_defaults(): void
    {
        $owner = User::factory()->sales()->create();
        $csv = "\xEF\xBB\xBFFirst name,Last name,Email address,Phone number,Country/Region\n Alex , Example ,alex@example.com,+61 400 123 456,Australia\n,Only,,,India\nDuplicate,Email,ALEX@example.com,,\nDuplicate,Phone,,+61400123456,\n";
        $this->actingAs($owner)->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $csv)])
            ->assertSessionHasNoErrors()->assertRedirect(route('sales.contacts'))
            ->assertSessionHas('success', '2 contacts imported. 2 duplicates skipped.');
        $this->assertDatabaseCount('contacts', 2);
        $this->assertDatabaseHas('contacts', ['name' => 'Alex Example', 'email' => 'alex@example.com', 'phone' => '+61 400 123 456', 'country_region' => 'Australia', 'status' => 'new', 'owner_id' => $owner->id]);
        $this->assertDatabaseHas('contacts', ['name' => 'Only', 'country_region' => 'India', 'email' => null, 'phone' => null]);
        $contact = Contact::where('email', 'alex@example.com')->firstOrFail();
        $this->get(route('sales.contacts.edit', $contact))->assertOk()->assertSee('Australia');
        $this->put(route('sales.contacts.update', $contact), ['name' => $contact->name, 'status' => 'new', 'country_region' => 'New Zealand'])->assertSessionHasNoErrors();
        $this->assertSame('New Zealand', $contact->fresh()->country_region);
    }

    public function test_invalid_rows_reject_the_entire_file(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        foreach ([',,other@example.com,,', 'Invalid,Email,not-an-email,,', 'Invalid,Country,,,'.str_repeat('x', 151)] as $invalidRow) {
            $csv = "First name,Last name,Email address,Phone number,Country/Region\nValid,Contact,valid@example.com,,Australia\n{$invalidRow}\n";
            $this->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $csv)])
                ->assertSessionHasErrors('contact_csv')
                ->assertSessionHas('errors', fn ($errors): bool => str_contains($errors->first('contact_csv'), 'Row 3:'));
            $this->assertDatabaseCount('contacts', 0);
        }
    }

    public function test_template_uses_requested_headers_and_imports(): void
    {
        $this->actingAs(User::factory()->sales()->create());
        $sample = $this->get(route('sales.contacts.sample'))->assertOk()->assertDownload('contacts-sample.csv')->streamedContent();
        $this->assertSame(['First name', 'Last name', 'Email address', 'Phone number', 'Country/Region'], str_getcsv(strtok($sample, "\n"), escape: ''));
        $this->post(route('sales.contacts.import'), ['contact_csv' => UploadedFile::fake()->createWithContent('contacts.csv', $sample)])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contacts', ['name' => 'Alex Example', 'country_region' => 'Australia']);
    }
}
