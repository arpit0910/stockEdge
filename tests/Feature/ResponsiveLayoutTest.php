<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\Stock;
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
            ->assertSee('aria-controls="navigation"', false)
            ->assertSee('id="site-footer"', false)
            ->assertSee('id="footer-newsletter-email"', false)
            ->assertSee('name="type" value="newsletter"', false)
            ->assertSeeInOrder(['Explore', 'Latest Research', 'Important Links', 'Privacy Policy', 'Get in Touch'])
            ->assertSee('sr-lead-desc-desktop', false)
            ->assertSee('Evaluate investment opportunities by analysing business fundamentals', false)
            ->assertSee('sr-lead-desc-mobile', false)
            ->assertSee('Join the SharesRise community for expert investment insights', false);

        $member = User::factory()->create();
        $this->actingAs($member)->get('/dashboard')
            ->assertOk()
            ->assertSee('width=device-width, initial-scale=1', false)
            ->assertSee('data-dashboard-theme-toggle', false)
            ->assertSee('dashboard-theme', false);
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
            ->assertSee('aria-controls="admin-sidebar"', false)
            ->assertSee('data-theme-toggle', false);
    }

    public function test_stylesheets_include_phone_breakpoints_and_horizontal_table_guards(): void
    {
        $publicCss = file_get_contents(public_path('css/app.css'));
        $workspaceCss = file_get_contents(public_path('css/admin.css'));

        $this->assertStringContainsString('@media (max-width: 380px)', $publicCss);
        $this->assertStringContainsString('.table-scroll, .sr-table-responsive', $publicCss);
        $this->assertStringContainsString('.sr-testimonials-grid { grid-auto-columns: 100%;', $publicCss);
        $this->assertStringContainsString('grid-auto-columns: calc((100% - 66px) / 4);', $publicCss);
        $this->assertStringContainsString('.sr-category-cards-grid {', $publicCss);
        $this->assertStringContainsString('grid-auto-columns: 100%;', $publicCss);
        $this->assertStringContainsString('content: attr(data-label)', $publicCss);
        $this->assertStringContainsString('.sr-footer-grid {', $publicCss);
        $this->assertStringContainsString('grid-template-columns: 1fr;', $publicCss);
        $this->assertStringContainsString('.sr-pricing-section .price-card { color: var(--slate-900); }', $publicCss);
        $this->assertStringContainsString('.sr-faq-section { padding-block: 44px; }', $publicCss);
        $this->assertStringContainsString('.sr-lead-gen-section { margin-block: 24px 0; }', $publicCss);
        $this->assertStringContainsString('@media(max-width:420px)', $workspaceCss);
        $this->assertStringContainsString('.a-table-scroll{overscroll-behavior-inline:contain', $workspaceCss);
        $this->assertStringContainsString('.prose .table-scroll,', $publicCss);
        $this->assertStringContainsString('.internal-article-body .table-scroll', $publicCss);
        $this->assertStringContainsString('overflow-x: clip;', $publicCss);
        $this->assertStringContainsString('.sr-lead-desc-desktop', $publicCss);
        $this->assertStringContainsString('.sr-lead-desc-mobile', $publicCss);
        $this->assertStringContainsString('.detail-snapshot-mobile', $publicCss);
    }

    public function test_report_detail_page_is_mobile_responsive_and_wraps_tables(): void
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create(['symbol' => 'WZR', 'name' => 'Wisr Limited']);
        $htmlTable = '<table><thead><tr><th>Metric</th><th>FY25</th></tr></thead><tbody><tr><td>Revenue</td><td>$25.8m</td></tr></tbody></table>';
        $report = Report::factory()->create([
            'stock_id' => $stock->id,
            'slug' => 'wisr-limited-asx-wzr',
            'title' => 'Wisr Limited',
            'body_format' => 'html',
            'body' => '<p>Company overview</p>'.$htmlTable.'<p class="data">Source: ASX</p>',
            'published' => true,
            'premium' => false,
        ]);

        $response = $this->actingAs($user)->get('/reports/'.$report->slug);
        $response->assertOk();
        $response->assertSee('class="table-scroll sr-table-scroll"', false);
        $response->assertSee('detail-snapshot-mobile', false);
        $response->assertSee('<table', false);
        $response->assertSee('Wisr Limited');

        $js = file_get_contents(public_path('js/app.js'));
        $this->assertStringContainsString('wrapReportTables', $js);
    }
}
