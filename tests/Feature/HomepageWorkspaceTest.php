<?php

namespace Tests\Feature;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_graph_is_hidden_without_a_positive_price_and_target(): void
    {
        $stock = Stock::factory()->create();
        foreach ([[0, 0.83], [20, null], [20, 0]] as [$price, $target]) {
            $stock->update(['price' => $price, 'target_price' => $target]);
            $this->get('/')->assertOk()->assertSee($stock->name)
                ->assertDontSee('class="sr-dash-chart"', false)
                ->assertDontSee('class="sr-svg-chart"', false)
                ->assertDontSee('Price vs Coverage Target');
        }
        $stock->update(['price' => 20, 'target_price' => 25]);
        $this->get('/')->assertOk()->assertSee('class="sr-dash-chart"', false)
            ->assertSee('class="sr-svg-chart"', false)->assertSee('Price vs Coverage Target');
    }

    public function test_workspace_uses_first_top_pick_and_updates_with_company_coverage(): void
    {
        Stock::factory()->create(['homepage_position' => 0, 'name' => 'Homepage fallback']);
        Stock::factory()->create(['top_asx_pick' => true, 'homepage_position' => 20]);
        $stock = Stock::factory()->create(['top_asx_pick' => true, 'show_on_homepage' => false, 'homepage_position' => 1, 'symbol' => 'DYN', 'name' => 'Dynamic Company', 'price' => 20, 'target_price' => 25, 'change' => -2.5, 'yield' => 3.75, 'recommendation_status' => 'Closed']);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', fn (Stock $selected): bool => $selected->is($stock))
            ->assertSee('Dynamic Company')->assertSee('$20.00')->assertSee('$25.00')->assertSee('-2.50%')
            ->assertSee('+25.0%')->assertSee('3.75%')->assertSee('BELOW TARGET')->assertSee('Closed')
            ->assertDontSee('7,812.60')->assertDontSee('Intrinsic Margin of Safety')->assertDontSee('CONVICTION: BUY');
        $stock->update(['price' => 30, 'name' => 'Updated Company']);
        $this->get('/')->assertOk()->assertSee('Updated Company')->assertSee('$30.00')->assertSee('ABOVE TARGET')->assertSee('-16.7%');
    }

    public function test_workspace_falls_back_to_first_homepage_company_and_handles_missing_values(): void
    {
        Stock::factory()->create(['show_on_homepage' => false, 'top_asx_pick' => false]);
        $stock = Stock::factory()->create(['price' => 0, 'target_price' => null, 'change' => null, 'homepage_position' => 1]);
        Stock::factory()->create(['homepage_position' => 20]);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', fn (Stock $selected): bool => $selected->is($stock))
            ->assertSee('NOT SET')->assertSee('Target: Unavailable')->assertSee('Change unavailable')->assertSee('Unavailable');
        $stock->update(['price' => 10, 'target_price' => 10]);
        $this->get('/')->assertOk()->assertSee('AT TARGET')->assertSee('+0.0%');
    }

    public function test_workspace_has_an_empty_state_without_selected_companies(): void
    {
        Stock::factory()->create(['show_on_homepage' => false, 'top_asx_pick' => false]);
        $this->get('/')->assertOk()->assertViewHas('workspaceStock', null)
            ->assertSee('Company coverage will appear here')->assertDontSee('Valuation Model')->assertDontSee('7,812.60');
    }
}
