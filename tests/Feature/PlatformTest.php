<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Report;
use App\Models\Stock;
use App\Models\Taxonomy;
use App\Models\User;
use Database\Seeders\AdminContentSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_pages_and_seeded_links_render(): void
    {
        $this->seed();
        foreach (['/', '/research', '/insights', '/about-us', '/contact', '/privacy', '/terms', '/disclaimer', '/financial-services-guide', '/free-report', '/performance', '/retirement', '/pricing', '/sectors', '/sample-report', '/login', '/register', '/forgot-password'] as $path) {
            $this->get($path)->assertOk();
        }
        $this->get('/about-us')
            ->assertSee('sr-about-split-layout', false)
            ->assertSee('sr-coverage-cards-grid', false);
        $this->get('/')
            ->assertSeeInOrder(['Research Collections', 'Discover High-Potential Investment Opportunities.', 'Market Insights'])
            ->assertSee('href="'.route('sample').'"', false)
            ->assertSeeInOrder(['Send my free report', 'Frequently asked questions']);
        $this->get('/contact')
            ->assertSee('sr-contact-hero-points', false)
            ->assertSee('sr-enquiry-heading', false)
            ->assertDontSee('I\'m not a robot');
        $this->get('/about')->assertRedirect('/about-us');
        foreach (Report::all() as $report) {
            $this->get('/reports/'.$report->slug)->assertOk();
        }
        foreach (Article::all() as $article) {
            $this->get('/insights/'.$article->slug)->assertOk();
        }
        foreach (Stock::all() as $stock) {
            $this->get('/stocks/'.$stock->symbol)->assertOk();
        }
        $this->get('/not-a-page')->assertNotFound();
    }

    public function test_research_search_and_filters_find_only_matching_content(): void
    {
        $this->seed();
        $this->get('/research?q=BHP')->assertOk()->assertSee('BHP Group')->assertDontSee('CSL Limited:');
        $this->get('/research?sector=Mining&category=Daily%20Analysis')->assertOk()->assertSee('BHP Group: the Daily Analysis perspective')->assertDontSee('Woodside Energy:');
        $this->get('/research?q=doesnotexist')->assertOk()->assertSee('No reports match');
        $this->get('/insights?topic=ETF%20News')->assertOk()->assertSee('Understanding the holdings behind an ETF')->assertDontSee('Better questions make better investors');
    }

    public function test_home_displays_only_published_testimonials(): void
    {
        DB::table('testimonials')->insert([
            [
                'name' => 'Visible Investor',
                'role' => 'Long-term member',
                'quote' => 'The research gives me a clearer investment process.',
                'rating' => 5,
                'position' => 1,
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hidden Investor',
                'role' => 'Draft testimonial',
                'quote' => 'This draft must stay hidden.',
                'rating' => 4,
                'position' => 2,
                'published' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Visible Investor')
            ->assertSee('The research gives me a clearer investment process.')
            ->assertDontSee('Hidden Investor');
    }

    public function test_footer_displays_configured_social_profiles(): void
    {
        DB::table('site_settings')->where('key', 'social_facebook')->update(['value' => 'https://facebook.example/sharesrise']);
        DB::table('site_settings')->where('key', 'social_linkedin')->update(['value' => 'https://linkedin.example/sharesrise']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Follow Us')
            ->assertSee('href="https://facebook.example/sharesrise"', false)
            ->assertSee('href="https://linkedin.example/sharesrise"', false);
    }

    public function test_home_research_collections_come_from_published_backend_categories(): void
    {
        Taxonomy::factory()->create([
            'name' => 'Backend Collection',
            'display_name' => 'Backend Collection Card',
            'label' => 'BACKEND MANAGED',
            'description' => 'A category created through the backend.',
            'position' => 2,
            'published' => true,
        ]);
        Taxonomy::factory()->create([
            'name' => 'Hidden Collection',
            'display_name' => 'Hidden Collection Card',
            'position' => 1,
            'published' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Backend Collection Card')
            ->assertSee('BACKEND MANAGED')
            ->assertSee('A category created through the backend.')
            ->assertSee(route('research', ['category' => 'Backend Collection']), false)
            ->assertDontSee('Hidden Collection Card');

        $this->get('/research')->assertOk()->assertSee('Backend Collection');
    }

    public function test_home_market_tables_use_company_coverage_records(): void
    {
        $this->seed(AdminContentSeeder::class);
        Stock::factory()->create([
            'symbol' => 'HPC',
            'name' => 'Homepage Company',
            'sector' => 'Mining',
            'price' => 51.25,
            'day_high' => 53,
            'day_low' => 49,
            'change' => -1.75,
            'buy_price' => 40,
            'target_price' => 50,
            'recommendation_status' => 'Target Achieved',
            'homepage_position' => 1,
            'show_on_homepage' => true,
        ]);
        Stock::factory()->create([
            'symbol' => 'HID',
            'name' => 'Hidden Homepage Company',
            'homepage_position' => 0,
            'show_on_homepage' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Homepage Company')
            ->assertSee('HPC')
            ->assertSee('$53.00')
            ->assertSee('$49.00')
            ->assertSee('-1.75%')
            ->assertSee('$40.00')
            ->assertSee('$50.00')
            ->assertSee('+25.0%')
            ->assertSee('Target Achieved')
            ->assertDontSee('Hidden Homepage Company');
    }

    public function test_registration_starts_trial_and_does_not_allow_admin_assignment(): void
    {
        $this->post('/register', ['name' => 'Example Investor', 'email' => 'investor@example.test', 'password' => 'SecurePass123!', 'password_confirmation' => 'SecurePass123!', 'consent' => 1, 'is_admin' => true])->assertRedirect('/dashboard');
        $user = User::first();
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->is_admin);
        $this->assertTrue($user->trial_ends_at->isFuture());
        $this->get('/dashboard')->assertOk()->assertSee('Example Investor');
    }

    public function test_login_logout_and_password_reset(): void
    {
        Notification::fake();
        $user = User::factory()->create(['password' => Hash::make('OldPassword123!')]);
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $user->email, 'password' => 'OldPassword123!'])->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHas('success');
        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });
        $this->post('/reset-password', ['token' => $token, 'email' => $user->email, 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!'])->assertRedirect('/login');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_premium_body_requires_active_trial_and_drafts_are_hidden(): void
    {
        $report = Report::factory()->create();
        $this->get('/reports/'.$report->slug)->assertOk()->assertDontSee('Confidential report body');
        $user = User::factory()->create(['trial_ends_at' => now()->addDay()]);
        $this->actingAs($user)->get('/reports/'.$report->slug)->assertSee('Confidential report body');
        $user->forceFill(['trial_ends_at' => now()->subDay()])->save();
        $this->get('/reports/'.$report->slug)->assertDontSee('Confidential report body');
        $report->update(['published' => false]);
        $this->get('/reports/'.$report->slug)->assertNotFound();
    }

    public function test_holdings_are_validated_valued_and_scoped_to_owner(): void
    {
        $stock = Stock::factory()->create(['price' => 12]);
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($owner)->post('/holdings', ['stock_id' => $stock->id, 'quantity' => 10, 'buy_price' => 8])->assertSessionHas('success');
        $holding = DB::table('holdings')->first();
        $this->get('/account/portfolio')->assertOk()->assertSee('$120.00')->assertSee('$40.00');
        $this->post('/holdings', ['stock_id' => $stock->id, 'quantity' => -1, 'buy_price' => 8])->assertSessionHasErrors('quantity');
        $this->actingAs($other)->delete('/account/holdings/'.$holding->id)->assertNotFound();
        $this->get('/account/portfolio')->assertDontSee('$120.00');
        $this->assertDatabaseHas('holdings', ['id' => $holding->id]);
        $this->actingAs($owner)->delete('/account/holdings/'.$holding->id)->assertSessionHas('success');
        $this->assertDatabaseMissing('holdings', ['id' => $holding->id]);
    }

    public function test_watchlist_updates_threshold_without_duplicates_and_is_private(): void
    {
        $stock = Stock::factory()->create(['price' => 10]);
        $owner = User::factory()->create();
        $this->actingAs($owner)->post('/watchlist', ['stock_id' => $stock->id, 'alert_price' => 12])->assertSessionHas('success');
        $this->post('/watchlist', ['stock_id' => $stock->id, 'alert_price' => 11])->assertSessionHas('success');
        $this->assertDatabaseCount('watchlists', 1);
        $this->get('/account/watchlist')->assertOk()->assertSee('Threshold reached');
        $id = DB::table('watchlists')->value('id');
        $this->actingAs(User::factory()->create())->delete('/account/watchlists/'.$id)->assertNotFound();
    }

    public function test_lead_consent_and_contact_validation_and_sample_redirect(): void
    {
        $this->post('/enquiries', ['email' => 'reader@example.test', 'type' => 'newsletter'])->assertSessionHasErrors('consent');
        $this->assertDatabaseCount('leads', 0);
        $this->post('/enquiries', ['email' => 'reader@example.test', 'type' => 'contact', 'consent' => 1])->assertSessionHasErrors(['name', 'message']);
        $this->post('/enquiries', ['email' => 'reader@example.test', 'name' => 'Reader', 'type' => 'report', 'consent' => 1])->assertRedirect('/sample-report');
        $this->assertDatabaseHas('leads', ['email' => 'reader@example.test', 'type' => 'report', 'consent' => 1]);
    }

    public function test_pending_subscription_does_not_unlock_expired_premium_access(): void
    {
        $user = User::factory()->create(['trial_ends_at' => now()->subDay()]);
        $report = Report::factory()->create();
        $this->actingAs($user)->post('/subscribe', ['plan' => 'Investor', 'billing' => 'yearly'])->assertRedirect('/account/subscription');
        $this->assertDatabaseHas('subscription_requests', ['user_id' => $user->id, 'status' => 'pending']);
        $this->get('/reports/'.$report->slug)->assertDontSee('Confidential report body');
        $this->get('/account/subscription')->assertOk()->assertSee('No charge has been made');
    }

    public function test_admin_permissions_and_content_publishing(): void
    {
        $member = User::factory()->create();
        $this->actingAs($member)->get('/admin')->assertForbidden();
        $this->post('/admin/article', ['title' => 'Unapproved'])->assertForbidden();
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin')->assertOk();
        $data = ['title' => 'A new perspective', 'slug' => 'a-new-perspective', 'summary' => 'A fresh view', 'body' => "Heading\nResearch text", 'topic' => 'Blogs', 'published' => 1];
        $this->post('/admin/article', $data)->assertSessionHas('success');
        $this->get('/insights/a-new-perspective')->assertOk()->assertSee('Research text');
        $article = Article::first();
        $this->post('/admin/article', $data + ['id' => $article->id])->assertSessionHas('success');
        $this->delete('/admin/article/'.$article->id)->assertSessionHas('success');
        $this->get('/insights/a-new-perspective')->assertNotFound();
    }

    public function test_member_routes_require_authentication(): void
    {
        foreach (['/dashboard', '/account/portfolio', '/account/watchlist', '/account/subscription', '/admin'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }
}
