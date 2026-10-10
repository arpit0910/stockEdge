<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsiveLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_and_member_layouts_include_mobile_viewport_and_navigation(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('width=device-width, initial-scale=1', false)
            ->assertSee('class="menu-toggle"', false)
            ->assertSee('aria-controls="navigation"', false);

        $member = User::factory()->create();
        $this->actingAs($member)->get('/dashboard')
            ->assertOk()
            ->assertSee('width=device-width, initial-scale=1', false);
    }

    public function test_admin_and_sales_layouts_include_mobile_viewport_and_sidebar_controls(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('width=device-width,initial-scale=1', false)
            ->assertSee('aria-controls="admin-sidebar"', false);

        $salesUser = User::factory()->sales()->create();
        $this->actingAs($salesUser)->get('/sales')
            ->assertOk()
            ->assertSee('width=device-width,initial-scale=1', false)
            ->assertSee('aria-controls="admin-sidebar"', false);
    }

    public function test_stylesheets_include_phone_breakpoints_and_horizontal_table_guards(): void
    {
        $publicCss = file_get_contents(public_path('css/app.css'));
        $workspaceCss = file_get_contents(public_path('css/admin.css'));

        $this->assertStringContainsString('@media (max-width: 380px)', $publicCss);
        $this->assertStringContainsString('.table-scroll, .sr-table-responsive', $publicCss);
        $this->assertStringContainsString('@media(max-width:420px)', $workspaceCss);
        $this->assertStringContainsString('.a-table-scroll{overscroll-behavior-inline:contain', $workspaceCss);
    }
}
