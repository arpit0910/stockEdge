<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\User;
use Database\Seeders\AdminContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_regular_members_cannot_access_admin_workspace(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/settings')->assertRedirect('/login');
        $this->get('/admin/manage/reports')->assertRedirect('/login');

        $member = User::factory()->create(['is_admin' => false]);
        $this->actingAs($member)->get('/admin')->assertForbidden();
        $this->actingAs($member)->get('/admin/settings')->assertForbidden();
        $this->actingAs($member)->get('/admin/manage/reports')->assertForbidden();
    }

    public function test_admin_can_access_overview_and_manage_resources(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('The research desk.');
        $this->actingAs($admin)->get('/admin/manage/pages')->assertOk()->assertSee('Website pages');
        $this->actingAs($admin)->get('/admin/manage/sections')->assertOk()->assertSee('Homepage builder');
        $this->actingAs($admin)->get('/admin/activity')->assertOk()->assertSee('Audit trail');
        $this->actingAs($admin)->get('/admin/media')->assertOk()->assertSee('Media library');
    }

    public function test_admin_can_create_a_report_category_and_import_an_html_report(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();
        $stock = Stock::factory()->create(['symbol' => 'ANO', 'name' => 'Advance ZincTek Limited']);
        $secondStock = Stock::factory()->create(['symbol' => 'BNO', 'name' => 'Beta Nickel Limited']);

        $this->actingAs($admin)->post('/admin/manage/report-categories', [
            'name' => 'Special Situations',
            'description' => 'Event-driven company research.',
            'position' => 5,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('taxonomies', [
            'kind' => 'category',
            'name' => 'Special Situations',
        ]);
        $this->get('/admin/manage/reports')
            ->assertOk()
            ->assertSee('Import HTML files')
            ->assertSee('name="report_html[]"', false)
            ->assertSee('Special Situations');
        $this->get('/admin/manage/reports/create')
            ->assertOk()
            ->assertSee('Special Situations')
            ->assertDontSee('name="report_html"', false);

        $html = '<!doctype html><html><head><title>Advance ZincTek Limited (ANO) | Historical ASX Research</title><meta name="description" content="Historical analysis of Advance ZincTek Limited."><style>body { color: red; }</style></head><body><header>Imported branding</header><main><section><h1>Advance ZincTek Limited</h1><p>ASX:ANO</p><p>Report date: 29-Jan-2026</p><p>Original stance: Buy</p></section><article class="body"><p>Opening analysis.</p><h2>Financial Summary</h2><p class="data">Revenue increased.</p><a href="javascript:alert(1)" onclick="alert(2)">Unsafe link</a><script>alert("blocked")</script></article></main><footer>Imported footer</footer></body></html>';
        $secondHtml = str_replace(
            ['Advance ZincTek Limited', '(ANO)', 'ASX:ANO', 'Original stance: Buy'],
            ['Beta Nickel Limited', '(BNO)', 'ASX:BNO', 'Original stance: Hold'],
            $html,
        );
        $response = $this->post('/admin/manage/reports/import', [
            'category' => 'Special Situations',
            'report_html' => [
                UploadedFile::fake()->createWithContent('advance-zinctek-limited-asx-ano.html', $html),
                UploadedFile::fake()->createWithContent('beta-nickel-limited-asx-bno.html', $secondHtml),
            ],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'stock_id' => $stock->id,
            'title' => 'Advance ZincTek Limited',
            'slug' => 'advance-zinctek-limited-asx-ano',
            'category' => 'Special Situations',
            'rating' => 'Buy',
            'summary' => 'Historical analysis of Advance ZincTek Limited.',
            'body_format' => 'html',
            'published' => false,
        ]);
        $this->assertDatabaseHas('reports', [
            'stock_id' => $secondStock->id,
            'title' => 'Beta Nickel Limited',
            'slug' => 'beta-nickel-limited-asx-bno',
            'category' => 'Special Situations',
            'rating' => 'Hold',
            'published' => false,
        ]);
        $report = DB::table('reports')->where('slug', 'advance-zinctek-limited-asx-ano')->first();
        $this->assertStringStartsWith('2026-01-29', $report->created_at);
        $reportBody = $report->body;
        $this->assertStringContainsString('<h2>Financial Summary</h2>', $reportBody);
        $this->assertStringContainsString('<p class="data">Revenue increased.</p>', $reportBody);
        $this->assertStringNotContainsString('Imported branding', $reportBody);
        $this->assertStringNotContainsString('Imported footer', $reportBody);
        $this->assertStringNotContainsString('<script', $reportBody);
        $this->assertStringNotContainsString('javascript:', $reportBody);
        $this->assertStringNotContainsString('onclick=', $reportBody);
        $this->get('/research')->assertOk()->assertSee('Special Situations');
        $this->get('/reports/advance-zinctek-limited-asx-ano')->assertNotFound();
        DB::table('reports')->where('id', $report->id)->update(['published' => true]);
        $this->get('/reports/advance-zinctek-limited-asx-ano')
            ->assertOk()
            ->assertSee('<h2>Financial Summary</h2>', false)
            ->assertSee('<p class="data">Revenue increased.</p>', false)
            ->assertDontSee('Imported branding')
            ->assertDontSee('alert("blocked")');
    }

    public function test_html_report_with_a_missing_company_is_kept_for_review(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();
        $html = '<!doctype html><html><head><meta name="description" content="Historical analysis ready for review."></head><body><main><section><h1>Advance ZincTek Limited</h1><p>ASX:ANO</p><p>Report date: 29-Jan-2026</p><p>Original stance: Buy</p></section><article class="body"><p>Complete imported analysis.</p></article></main></body></html>';

        $this->actingAs($admin)->post('/admin/manage/reports/import', [
            'category' => 'Penny Stocks',
            'report_html' => [UploadedFile::fake()->createWithContent('advance-zinctek-limited-asx-ano.html', $html)],
        ])->assertSessionHas('success');

        $report = DB::table('reports')->where('slug', 'advance-zinctek-limited-asx-ano')->first();
        $this->assertNotNull($report);
        $this->assertNull($report->stock_id);
        $this->assertStringContainsString('Missing company record for ASX:ANO', $report->import_warnings);
        $this->get('/admin/manage/reports')->assertOk()->assertSee('Missing details');
        $this->get('/admin/manage/reports/'.$report->id.'/edit')
            ->assertOk()
            ->assertSee('This imported report needs details before approval.')
            ->assertSee('Missing company record for ASX:ANO');

        $stock = Stock::factory()->create(['symbol' => 'ANO']);
        $this->put('/admin/manage/reports/'.$report->id, [
            '_version' => $report->updated_at,
            'title' => $report->title,
            'slug' => $report->slug,
            'stock_id' => $stock->id,
            'category' => $report->category,
            'rating' => $report->rating,
            'summary' => $report->summary,
            'body' => $report->body,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'stock_id' => $stock->id,
            'import_warnings' => null,
        ]);
    }

    public function test_admin_can_save_settings(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->put('/admin/settings', [
            'brand_name' => 'StockEdge Updated',
            'announcement' => 'Independent company research and investor tools.',
            'footer_description' => 'Company research. Market context. Your investment workspace.',
            'newsletter_title' => 'The StockEdge briefing',
            'newsletter_description' => 'Research updates and market perspectives, in your inbox.',
            'contact_email' => 'support@example.test',
            'contact_phone' => '+61 2 9000 0000',
            'contact_address' => 'Sydney, Australia',
            'meta_description' => 'Updated meta description for testing.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('site_settings', ['key' => 'brand_name', 'value' => 'StockEdge Updated']);
    }

    public function test_admin_can_create_update_and_delete_custom_pages(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();

        $createResponse = $this->actingAs($admin)->post('/admin/manage/pages', [
            'title' => 'Investment Methodology',
            'slug' => 'investment-methodology',
            'eyebrow' => 'HOW WE WORK',
            'summary' => 'Our investment methodology explained.',
            'body' => "Methodology\nWe evaluate businesses on quality, moat, and cash flows.",
            'meta_description' => 'Discover our methodology.',
            'show_in_footer' => 1,
            'position' => 20,
            'published' => 1,
        ]);
        $createResponse->assertSessionHas('success');

        $page = DB::table('site_pages')->where('slug', 'investment-methodology')->first();
        $this->assertNotNull($page);

        $updateResponse = $this->actingAs($admin)->put('/admin/manage/pages/'.$page->id, [
            '_version' => $page->updated_at,
            'title' => 'Investment Methodology Updated',
            'slug' => 'investment-methodology',
            'eyebrow' => 'HOW WE WORK',
            'summary' => 'Our investment methodology updated.',
            'body' => "Methodology\nWe evaluate businesses on quality, moat, and cash flows.",
            'meta_description' => 'Discover our methodology.',
            'show_in_footer' => 1,
            'position' => 20,
            'published' => 1,
        ]);
        $updateResponse->assertSessionHas('success');

        $pageUpdated = DB::table('site_pages')->where('id', $page->id)->first();
        $deleteResponse = $this->actingAs($admin)->delete('/admin/manage/pages/'.$page->id, [
            '_version' => $pageUpdated->updated_at,
        ]);
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('site_pages', ['id' => $page->id]);
    }

    public function test_admin_cannot_delete_system_pages(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();

        $aboutPage = DB::table('site_pages')->where('slug', 'about')->first();
        $this->actingAs($admin)->delete('/admin/manage/pages/'.$aboutPage->id, [
            '_version' => $aboutPage->updated_at,
        ])->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('site_pages', ['id' => $aboutPage->id]);
    }

    public function test_admin_can_reorder_sections(): void
    {
        $this->seed(AdminContentSeeder::class);
        $admin = User::factory()->admin()->create();

        $sections = DB::table('site_sections')->orderBy('position')->orderBy('id')->get();
        $first = $sections->first();
        $second = $sections->get(1);

        $this->actingAs($admin)->post('/admin/sections/'.$first->id.'/reorder', [
            'direction' => 'down',
        ])->assertSessionHas('success');

        $firstAfter = DB::table('site_sections')->where('id', $first->id)->first();
        $secondAfter = DB::table('site_sections')->where('id', $second->id)->first();

        $this->assertTrue($firstAfter->position > $secondAfter->position);
    }

    public function test_suspended_member_is_logged_out_by_middleware(): void
    {
        $member = User::factory()->create(['is_active' => false]);

        $this->actingAs($member)->get('/dashboard')
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
