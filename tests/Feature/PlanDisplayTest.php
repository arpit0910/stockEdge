<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Database\Seeders\AdminContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_only_displays_three_selected_published_plans_in_order(): void
    {
        $plans = Plan::factory()->count(4)->sequence(
            ['position' => 4], ['position' => 3], ['position' => 2], ['position' => 1],
        )->create(['show_on_homepage' => true]);
        $hidden = Plan::factory()->create(['show_on_homepage' => false, 'name' => 'Hidden membership']);
        $draft = Plan::factory()->create(['show_on_homepage' => true, 'published' => false, 'position' => 0, 'name' => 'Draft membership']);

        $this->get('/')->assertOk()
            ->assertSeeInOrder([$plans[3]->name, $plans[2]->name, $plans[1]->name])
            ->assertDontSee($hidden->name)->assertDontSee($draft->name)
            ->assertViewHas('plans', fn ($plans): bool => $plans->count() === 3);
    }

    public function test_plan_prices_omit_zero_cents_and_keep_nonzero_cents(): void
    {
        $this->seed(AdminContentSeeder::class);
        Plan::query()->delete();
        Plan::factory()->create(['yearly_price' => 1234, 'monthly_price' => 111, 'show_on_homepage' => true]);
        Plan::factory()->create(['yearly_price' => 49.95, 'monthly_price' => 222, 'show_on_homepage' => true]);
        $pricingOnly = Plan::factory()->create(['show_on_homepage' => false]);

        foreach (['/', '/pricing'] as $path) {
            $this->get($path)->assertOk()->assertSee('$1,234</span>', false)
                ->assertDontSee('$1,234.00')->assertSee('$49.95</span>', false)
                ->assertSee('/ year')->assertDontSee('/ month')
                ->assertDontSee('data-billing', false)
                ->assertDontSee('$111</span>', false)->assertDontSee('$222</span>', false);
        }
        $this->get('/pricing')->assertSee($pricingOnly->name);
    }

    public function test_plan_requests_only_accept_yearly_billing(): void
    {
        $this->seed(AdminContentSeeder::class);
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['published' => true, 'is_trial' => false]);

        $this->actingAs($user)->post('/subscribe', ['plan' => $plan->name, 'billing' => 'monthly'])
            ->assertSessionHasErrors('billing');
        $this->assertDatabaseCount('subscription_requests', 0);

        $this->post('/subscribe', ['plan' => $plan->name, 'billing' => 'yearly'])
            ->assertSessionHasNoErrors()->assertRedirect('/account/subscription');
        $this->assertDatabaseHas('subscription_requests', ['user_id' => $user->id, 'billing' => 'yearly']);
        $this->get('/pricing')->assertOk()->assertSee('name="billing" value="yearly"', false);
    }

    public function test_admin_cannot_select_a_fourth_plan_and_can_replace_a_selection(): void
    {
        $admin = User::factory()->admin()->create();
        $selected = Plan::factory()->count(3)->create(['show_on_homepage' => true]);
        $plan = Plan::factory()->create(['show_on_homepage' => false]);
        $payload = $plan->only(['name', 'headline', 'description', 'features', 'monthly_price', 'yearly_price', 'is_trial', 'featured', 'published', 'position'])
            + ['show_on_homepage' => true, '_version' => $plan->updated_at->format('Y-m-d H:i:s')];

        $this->actingAs($admin)->get('/admin/manage/plans/'.$plan->id.'/edit')->assertOk()->assertSee('Show on homepage');
        $this->put('/admin/manage/plans/'.$plan->id, $payload)->assertSessionHasErrors('show_on_homepage');
        $this->assertDatabaseHas('plans', ['id' => $plan->id, 'show_on_homepage' => false]);

        $existing = $selected[0];
        $existingPayload = $existing->only(array_keys($payload));
        $existingPayload['_version'] = $existing->updated_at->format('Y-m-d H:i:s');
        $this->put('/admin/manage/plans/'.$existing->id, $existingPayload)->assertSessionHasNoErrors();
        $existingPayload['show_on_homepage'] = false;
        $existingPayload['_version'] = $existing->fresh()->getRawOriginal('updated_at');
        $this->put('/admin/manage/plans/'.$existing->id, $existingPayload)->assertSessionHasNoErrors();
        $this->put('/admin/manage/plans/'.$plan->id, $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('plans', ['id' => $plan->id, 'show_on_homepage' => true]);
    }
}
