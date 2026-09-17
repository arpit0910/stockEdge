<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminContentSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = require config_path('stockedge.php');
        foreach (['categories' => 'category', 'sectors' => 'sector', 'topics' => 'topic'] as $key => $kind) {
            foreach ($defaults[$key] as $i => $name) {
                $this->insert('taxonomies', ['kind' => $kind, 'name' => $name], ['description' => 'Explore '.$name.' research and perspectives.', 'position' => $i + 1]);
            }
        }
        $sections = [
            ['Opening story', 'hero', 'INDEPENDENT EQUITY RESEARCH', 'Research the business. Understand the investment.', 'Company analysis, market context and a place to keep your own investment thinking organised.', 'Explore the research', '/research', 4],
            ['Research desk', 'research', 'FROM THE RESEARCH DESK', 'The latest company coverage.', 'Read the investment case, examine the risks and decide what deserves a closer look.', 'All research', '/research', 4],
            ['Market coverage', 'market', 'AUSTRALIAN EQUITIES', 'Companies on our radar.', 'A working view of the companies in our coverage universe. Prices shown are demonstration snapshots.', 'Explore sectors', '/sectors', 6],
            ['Research collections', 'collections', 'CHOOSE YOUR RESEARCH LENS', 'Follow a theme.', 'Find the research collections relevant to your interests and investment horizon.', 'All collections', '/research', 4],
            ['Investor tools', 'tools', 'YOUR INVESTOR WORKSPACE', 'Keep your research and holdings together.', 'Track positions, save companies and revisit the assumptions behind your decisions.', 'Open your workspace', '/dashboard', 3],
            ['Editorial desk', 'editorial', 'MARKET CONTEXT', 'Beyond individual companies.', 'Develop a broader understanding of the industries, trends and decisions that shape investing.', 'All insights', '/insights', 3],
            ['Trial invitation', 'callout', 'START EXPLORING', 'Take a closer look at StockEdge.', 'Explore the research library with seven days of member access.', 'Start your free trial', '/register', 3],
        ];
        foreach ($sections as $i => [$name,$layout,$eyebrow,$title,$description,$button_label,$button_url,$item_limit]) {
            $this->insert('site_sections', ['name' => $name], compact('layout', 'eyebrow', 'title', 'description', 'button_label', 'button_url', 'item_limit') + ['position' => ($i + 1) * 10, 'published' => true]);
        }
        foreach ([
            ['Starter', 'Perfect for new investors', 'Daily market updates, 2 stock reports/month and essential tools.', 19, 190, true, false, "Daily Market Updates\n2 Stock Reports / Month\nAccess to Basic Tools\nEmail Support"],
            ['Investor', 'For serious investors', 'Everything you need for consistent Australian equity market outperformance.', 39, 390, false, true, "All Starter Features\n5 Stock Reports / Month\nModel Portfolios\nPriority Email Support"],
            ['Pro', 'For active traders & pros', 'Full unrestricted research coverage, advanced screeners and priority support.', 79, 790, false, false, "All Investor Features\nUnlimited Stock Reports\nAdvanced Screeners\nPriority Support"],
        ] as $i => [$name,$headline,$description,$monthly_price,$yearly_price,$is_trial,$featured,$features]) {
            $this->insert('plans', ['name' => $name], compact('headline', 'description', 'monthly_price', 'yearly_price', 'is_trial', 'featured', 'features') + ['published' => true, 'position' => $i + 1]);
        }
        $pages = [
            'about' => ['About SharesRise', 'Independent research. Practical tools.', 'A platform built around Australian company research and considered investing.', "Our approach\nStart with the business, examine the assumptions and keep the risks in view. SharesRise combines company research with a workspace for your holdings and watchlist.\n\nOur current stage\nThis platform contains demonstration research and market snapshots. Commercial services and analyst credentials will be established before launch."],
            'contact' => ['Contact the team', 'A QUESTION ABOUT SHAERSRISE?', 'Ask about research coverage, memberships or using the platform.', "Getting in touch\nSubmit the form below. Your enquiry will be available to the site administrator."],
            'privacy' => ['Privacy policy', 'PLATFORM INFORMATION · DRAFT', 'Draft privacy information for this demonstration.', "Information we store\nWe store account details, password hashes, holdings, watchlists and enquiries submitted through the website.\n\nYour information\nThe data supports the features you request. Administrators review enquiries and membership requests.\n\nBefore launch\nThe operator must publish approved retention, access, deletion and processor details before commercial use."],
            'terms' => ['Terms of use', 'PLATFORM INFORMATION · DRAFT', 'Draft terms for the SharesRise demonstration.', "Using this platform\nPrices, research and ratings are examples. Do not rely on this demonstration for trading decisions.\n\nMembership\nA plan request does not charge your account or activate paid access.\n\nBefore launch\nCommercial terms, cancellation conditions and operator details must be finalised before launch."],
            'disclaimer' => ['Disclaimer', 'PLATFORM INFORMATION', 'Understand the scope of this demonstration.', "Example information\nContent, quotes and ratings are illustrative and are not current recommendations.\n\nInvestment risk\nInvestment values can fall as well as rise. Past performance does not predict future returns.\n\nCalculation tools\nPortfolio values use demo snapshots. The retirement calculator is a mathematical illustration and excludes fees, inflation and taxes."],
            'financial-services-guide' => ['Financial services guide', 'PLATFORM INFORMATION · DRAFT', 'A commercial Financial Services Guide has not yet been issued.', "Current status\nSharesRise is a demonstration platform. No financial services licence or regulatory authorisation is claimed.\n\nBefore commercial launch\nThe operator must establish its authorisation requirements and publish approved disclosures."],
            'pricing' => ['Membership plans', 'CHOOSE YOUR RESEARCH ACCESS', 'An option for each stage of your research journey.', "Membership requests\nThese are illustrative plans. Payment processing and commercial activation are not connected."],
            'retirement' => ['Retirement planning', 'YOUR NEXT CHAPTER', 'Explore how time and regular contributions affect an illustrative savings scenario.', "Using this tool\nThis calculator illustrates a constant-return savings scenario. It does not account for your personal circumstances."],
            'performance' => ['Performance methodology', 'CONTEXT MATTERS', 'Understand how investment performance is calculated.', "Our methodology\nThe examples below are fictional. SharesRise has no verified recommendation track record. Total return excludes fees, taxes and currency effects."],
            'free-report' => ['Your complimentary research guide', 'A PRACTICAL STARTING POINT', 'Get to know the questions behind a considered company research process.', "Inside the sample\nExplore business quality, cash generation, valuation assumptions and investment risks."],
            'sectors' => ['Sector insights', 'THE COVERAGE UNIVERSE', 'Explore the industries represented in our company research.', "Industry context\nUse a sector view to compare businesses operating in similar environments."],
        ];
        foreach ($pages as $slug => [$title,$eyebrow,$summary,$body]) {
            $this->insert('site_pages', ['slug' => $slug], compact('title', 'eyebrow', 'summary', 'body') + ['published' => true, 'system' => true, 'show_in_footer' => in_array($slug, ['about', 'contact', 'privacy', 'terms', 'disclaimer', 'financial-services-guide']), 'position' => 10]);
        }
        foreach (['brand_name' => 'SharesRise', 'announcement' => 'Australia Stock Market Research Platform · Modern, Trustworthy & Data-Driven.', 'footer_description' => 'Independent research. Expert insights. Smarter investments.', 'newsletter_title' => 'Stay updated with our latest research and market insights.', 'newsletter_description' => 'Research updates and market perspectives, in your inbox.', 'contact_email' => 'support@sharesrise.com.au', 'contact_phone' => '', 'contact_address' => 'Sydney, NSW, Australia', 'meta_description' => 'SharesRise: Independent Australian equity research, market insights and tools for confident investors.'] as $key => $value) {
            $this->insert('site_settings', ['key' => $key], ['value' => $value]);
        }
    }

    private function insert(string $table, array $key, array $values): void
    {
        if (! DB::table($table)->where($key)->exists()) {
            DB::table($table)->insert($key + $values + ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
