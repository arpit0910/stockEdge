<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StockCsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_preserves_ratings_and_normalizes_supplied_classifications(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        Stock::factory()->create(['symbol' => 'VSL', 'recommendation_status' => 'Closed']);
        $csv = <<<'CSV'
symbol,name,sector,cap,price,yield,recommendation_status
VSL,Vulcan Steel,materials,Small Cap,5.63,0.01,Buy
MLX,Metals X,Mining,Small Cap,1.695,0,Accumulate
MSB,Mesoblast,Healthcare,Mid Cap,2.5,0,Speculative Buy
SSM,Service Stream,Infrastructure,Small Cap,1.5,0,Hold
XRO,Xero,Technology,Large Cap / Blue-Chip Scale,100,0,Accumulate
BPT,Beach Energy,Energy,Mid Cap,0.96,0.04,Hold
EBO,EBOS Group,Healthcare,Mid Cap,17.08,0.05,Buy
MP1,Megaport,Technology,Mid Cap,16.18,0,Speculative Buy
360,Life360,Technology,Mid Cap,21.06,0,Buy
GDG,Generation Development Group,Financials,Small Cap,3.3,0.018,Hold
CSV;

        $this->post('/admin/manage/stocks/import', [
            'stock_csv' => UploadedFile::fake()->createWithContent('latest.csv', $csv),
        ])->assertSessionHasNoErrors()->assertSessionHas('success', 'Company CSV imported: 9 created, 1 updated.');

        $this->assertDatabaseHas('stocks', ['symbol' => 'VSL', 'sector' => 'Materials', 'investment_rating' => 'Buy', 'recommendation_status' => 'Closed']);
        $this->assertDatabaseHas('stocks', ['symbol' => 'MLX', 'investment_rating' => 'Accumulate', 'recommendation_status' => 'Active']);
        $this->assertDatabaseHas('stocks', ['symbol' => 'MSB', 'investment_rating' => 'Speculative Buy']);
        $this->assertDatabaseHas('stocks', ['symbol' => 'SSM', 'sector' => 'Infrastructure', 'investment_rating' => 'Hold']);
        $this->assertDatabaseHas('stocks', ['symbol' => 'XRO', 'cap' => 'Blue Chip']);

        $export = $this->get('/admin/manage/stocks/export')->assertOk()->streamedContent();
        $this->assertStringContainsString('investment_rating', $export);
        $this->post('/admin/manage/stocks/import', [
            'stock_csv' => UploadedFile::fake()->createWithContent('export.csv', $export),
        ])->assertSessionHasNoErrors()->assertSessionHas('success', 'Company CSV imported: 0 created, 10 updated.');
        $this->assertDatabaseHas('stocks', ['symbol' => 'VSL', 'investment_rating' => 'Buy', 'recommendation_status' => 'Closed']);
    }

    public function test_invalid_classifications_leave_all_existing_values_unchanged(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        Stock::factory()->create(['symbol' => 'VSL', 'price' => 5]);
        $csv = "symbol,price,cap\nVSL,10,Mid Cap\nNEW,5,Unknown Cap";

        $this->post('/admin/manage/stocks/import', [
            'stock_csv' => UploadedFile::fake()->createWithContent('invalid.csv', $csv),
        ])->assertSessionHasErrors('stock_csv', 'The cap must be Blue Chip, Mid Cap or Small Cap.');

        $this->assertDatabaseHas('stocks', ['symbol' => 'VSL', 'price' => 5]);
        $this->assertDatabaseMissing('stocks', ['symbol' => 'NEW']);
    }

    public function test_conflicting_ratings_are_rejected_instead_of_discarded(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $csv = "symbol,recommendation_status,investment_rating\nVSL,Buy,Sell";

        $this->post('/admin/manage/stocks/import', [
            'stock_csv' => UploadedFile::fake()->createWithContent('conflict.csv', $csv),
        ])->assertSessionHasErrors('stock_csv');

        $this->assertDatabaseCount('stocks', 0);
    }
}
