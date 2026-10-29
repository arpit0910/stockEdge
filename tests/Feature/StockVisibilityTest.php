<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StockVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_change_each_visibility_flag_without_changing_other_values(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $stock = Stock::factory()->create(['show_on_homepage' => false, 'top_asx_pick' => false, 'price' => 25]);
        foreach (['show_on_homepage', 'top_asx_pick'] as $field) {
            foreach ([1, 0] as $value) {
                $version = DB::table('stocks')->where('id', $stock->id)->value('updated_at');
                $this->from('/admin/manage/stocks?q=example')->patch(route('admin.stocks.visibility', $stock), [
                    'field' => $field, 'value' => $value, '_version' => $version,
                ])->assertRedirect('/admin/manage/stocks?q=example')->assertSessionHasNoErrors();
                $this->assertDatabaseHas('stocks', ['id' => $stock->id, $field => $value, 'price' => 25]);
            }
        }
        $this->assertDatabaseCount('admin_activities', 4);
        $this->get('/admin/manage/stocks')->assertOk()->assertSee(route('admin.stocks.visibility', $stock), false);
        $this->get(route('admin.edit', ['stocks', $stock->id]))->assertOk()
            ->assertSee('<select id="field-show_on_homepage"', false)
            ->assertSee('<select id="field-top_asx_pick"', false)
            ->assertDontSee('class="a-switch-row"', false);
    }

    public function test_homepage_card_visibility_is_independent(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $stock = Stock::factory()->create(['show_on_homepage' => false, 'show_in_latest_coverage' => false]);

        foreach ([[true, false], [true, true], [false, true], [false, false]] as [$radar, $latest]) {
            foreach (['show_on_homepage' => $radar, 'show_in_latest_coverage' => $latest] as $field => $value) {
                $this->patch(route('admin.stocks.visibility', $stock), [
                    'field' => $field,
                    'value' => (int) $value,
                    '_version' => DB::table('stocks')->where('id', $stock->id)->value('updated_at'),
                ])->assertSessionHasNoErrors()->assertRedirect();
            }

            $this->get(route('home'))->assertOk()
                ->assertViewHas('homepageStocks', fn ($stocks): bool => $stocks->contains('id', $stock->id) === $radar)
                ->assertViewHas('latestCoverageStocks', fn ($stocks): bool => $stocks->contains('id', $stock->id) === $latest);
        }

        $this->get(route('admin.records', 'stocks'))->assertOk()
            ->assertSee('COMPANIES ON OUR RADAR')
            ->assertSee('THE LATEST COMPANY COVERAGE')
            ->assertSee('role="switch"', false);
    }

    public function test_non_admins_cannot_change_visibility(): void
    {
        $stock = Stock::factory()->create(['show_on_homepage' => false]);
        $this->actingAs(User::factory()->create())->patch(route('admin.stocks.visibility', $stock), [
            'field' => 'show_on_homepage', 'value' => 1, '_version' => (string) $stock->updated_at,
        ])->assertForbidden();
        $this->assertDatabaseHas('stocks', ['id' => $stock->id, 'show_on_homepage' => false]);
    }

    public function test_invalid_fields_values_and_stale_versions_cannot_change_a_stock(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $stock = Stock::factory()->create(['show_on_homepage' => false, 'price' => 25]);
        foreach ([['price', 1, (string) $stock->updated_at], ['show_on_homepage', 'invalid', (string) $stock->updated_at], ['show_on_homepage', 1, 'outdated']] as [$field, $value, $version]) {
            $this->patch(route('admin.stocks.visibility', $stock), [
                'field' => $field, 'value' => $value, '_version' => $version,
            ])->assertSessionHasErrors();
        }
        $this->assertDatabaseHas('stocks', ['id' => $stock->id, 'show_on_homepage' => false, 'price' => 25]);
        $this->assertDatabaseCount('admin_activities', 0);
    }
}
