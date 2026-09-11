<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
