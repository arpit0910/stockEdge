<?php

namespace Tests\Feature;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_no_longer_renders_the_workspace_mockup(): void
    {
        Stock::factory()->create(['price' => 20, 'target_price' => 25]);
        $this->get('/')->assertOk()->assertDontSee('SharesRise research workspace')
            ->assertDontSee('class="sr-dash-chart"', false)->assertDontSee('class="sr-device-screen"', false);
    }

    public function test_workspace_uses_first_top_pick_and_updates_with_company_coverage(): void
    {
        Stock::factory()->create(['homepage_position' => 0, 'name' => 'Homepage fallback']);
        Stock::factory()->create(['top_asx_pick' => true, 'homepage_position' => 20]);
        $stock = Stock::factory()->create(['top_asx_pick' => true, 'show_on_homepage' => false, 'homepage_position' => 1, 'symbol' => 'DYN', 'name' => 'Dynamic Company', 'price' => 20, 'target_price' => 25, 'change' => -2.5, 'yield' => 3.75, 'recommendation_status' => 'Closed']);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', fn (Stock $selected): bool => $selected->is($stock));
    }

    public function test_workspace_falls_back_to_first_homepage_company_and_handles_missing_values(): void
    {
        Stock::factory()->create(['show_on_homepage' => false, 'top_asx_pick' => false]);
        $stock = Stock::factory()->create(['price' => 0, 'target_price' => null, 'change' => null, 'homepage_position' => 1]);
        Stock::factory()->create(['homepage_position' => 20]);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', fn (Stock $selected): bool => $selected->is($stock));
    }

    public function test_workspace_has_an_empty_state_without_selected_companies(): void
    {
        Stock::factory()->create(['show_on_homepage' => false, 'top_asx_pick' => false]);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', null)
            ->assertDontSee('SharesRise research workspace');
    }
}
