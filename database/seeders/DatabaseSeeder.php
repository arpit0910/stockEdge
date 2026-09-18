<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Report;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminContentSeeder::class);
        $rows = [
            ['BHP', 'BHP Group', 'Mining', 42.85, 1.24, 5.2, 'Blue Chip'],
            ['CBA', 'Commonwealth Bank', 'Banks', 132.64, 0.86, 3.4, 'Blue Chip'],
            ['CSL', 'CSL Limited', 'Healthcare', 287.32, -0.42, 1.2, 'Blue Chip'],
            ['WDS', 'Woodside Energy', 'Energy', 27.46, 2.18, 6.8, 'Blue Chip'],
            ['XRO', 'Xero Limited', 'Technology', 148.90, 1.67, 0, 'Mid Cap'],
            ['RIO', 'Rio Tinto', 'Materials', 119.75, 0.93, 5.7, 'Blue Chip'],
            ['FMG', 'Fortescue', 'Mining', 23.18, -1.12, 7.1, 'Blue Chip'],
            ['WBC', 'Westpac Banking', 'Banks', 28.72, 0.58, 4.9, 'Blue Chip'],
            ['QAN', 'Qantas Airways', 'Airlines', 6.32, 1.12, 0, 'Mid Cap'],
            ['SUN', 'Suncorp Group', 'Insurance', 17.85, -0.24, 4.3, 'Mid Cap'],
            ['WOW', 'Woolworths Group', 'Retail', 33.20, 0.35, 3.1, 'Blue Chip'],
            ['REA', 'REA Group', 'Real Estate', 198.20, 0.72, 1.1, 'Blue Chip'],
            ['MQG', 'Macquarie Group', 'Financials', 207.10, 0.81, 3.0, 'Blue Chip'],
            ['SEK', 'SEEK Limited', 'Services', 22.16, -0.63, 1.7, 'Mid Cap'],
            ['LTR', 'Liontown Resources', 'Mining', 0.82, 3.15, 0, 'Small Cap'],
        ];
        foreach ($rows as [$symbol,$name,$sector,$price,$change,$yield,$cap]) {
            Stock::updateOrCreate(['symbol' => $symbol], compact('name', 'sector', 'price', 'change', 'yield', 'cap') + ['description' => "$name is included in our $sector research coverage. Explore the operating environment, capital allocation and key risks. Prices shown are illustrative snapshots, not current market data."]);
        }
        foreach (config('stockedge.categories') as $i => $category) {
            foreach ([0, 1] as $j) {
                $stock = Stock::orderBy('id')->skip(($i + $j * 3) % count($rows))->first();
                $title = $j === 0 ? "$stock->name: the $category perspective" : "Inside $stock->symbol: fundamentals, catalysts and the road ahead";
                Report::updateOrCreate(['slug' => Str::slug($category.'-'.$stock->symbol)], [
                    'stock_id' => $stock->id, 'title' => $title, 'category' => $category, 'rating' => ['Buy', 'Hold', 'Sell'][($i + $j) % 3],
                    'summary' => "A closer look at $stock->name, with the business drivers, valuation questions and risks that matter to long-term investors.",
                    'body' => "Investment overview\nThis is an original demonstration report for $stock->name. It previews the SharesRise research experience and is not an investment recommendation.\n\nBusiness quality\nExamine cash generation, balance sheet resilience and competitive advantages. Compare operating margins across a complete business cycle.\n\nWhat to watch\nMonitor earnings releases, capital expenditure and changes in industry demand. A sound thesis identifies the evidence that would invalidate it.\n\nValuation and risks\nCompare multiple scenarios and allow for uncertainty. Sector concentration, interest rates and execution risks can change outcomes. Ratings and prices are fictional examples.\n\nResearch checklist\nRead company filings, check debt maturity dates and review cash conversion. This sample contains no verified forecasts or target prices.",
                    'premium' => $j === 1, 'published' => true,
                ]);
            }
        }
        $titles = [
            'Market News' => ['The big picture: reading the next chapter of the ASX', 'Five signals to watch in the next reporting season'],
            'Blogs' => ['Better questions make better investors', 'Building an investment process you can stick to'],
            'Mining Stories' => ['Beyond the headlines: Australia’s next resources cycle', 'Copper, conviction and the energy transition'],
            '52-Week Highs' => ['What a new high can tell you about a business', 'Momentum meets fundamentals: a research checklist'],
            'Dividend Investing' => ['Look beyond the yield: finding sustainable dividends', 'A practical guide to payout ratios and franking'],
            'ETF News' => ['A simpler way to explore a world of opportunities', 'Understanding the holdings behind an ETF'],
            'Retirement' => ['A thoughtful approach to your next chapter', 'Balancing income, growth and retirement goals'],
        ];
        foreach ($titles as $topic => $items) {
            foreach ($items as $i => $title) {
                Article::updateOrCreate(['slug' => Str::slug($title)], ['title' => $title, 'topic' => $topic, 'image' => ['city', 'mining', 'energy'][$i % 3],
                    'summary' => 'Step back from the daily noise. Explore the themes shaping markets and build a more considered investment approach.',
                    'body' => "The bigger picture\nMarkets bring a constant stream of information. A consistent research process helps put those signals in context. This is illustrative editorial content for the SharesRise demo.\n\nStart with the fundamentals\nRead company disclosures and distinguish recurring earnings from one-off items. Consider how cash flows behave under different conditions.\n\nKeep perspective\nDiversification can reduce exposure to a single business or sector, but cannot remove market risk. Review fees, liquidity and time horizon.\n\nBuild your checklist\nWrite down what you understand and what evidence you need. Revisit your checklist as information becomes available. This sample is educational and does not provide personal financial advice.", 'published' => true]);
            }
        }
    }
}
