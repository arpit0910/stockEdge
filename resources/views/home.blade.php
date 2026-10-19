@extends('layouts.app')

@section('title', 'Research the business. Understand the investment.')

@section('content')
<!-- =========================================================================
     1. HERO SECTION
     ========================================================================= -->
@php
    $heroSection = $siteSections->get('hero');
    $marketSection = $siteSections->get('market');
    $researchSection = $siteSections->get('research');
    $collectionsSection = $siteSections->get('collections');
    $editorialSection = $siteSections->get('editorial');
    $testimonialSection = $siteSections->get('testimonials');
@endphp
<section class="sr-hero">
    <div class="container sr-hero-grid">
        <!-- Left Content -->
        <div class="sr-hero-content">
            <h1 class="sr-hero-title">
                @if($heroSection && $heroSection->title)
                    {{ $heroSection->title }}
                @else
                    Research the business.<br>
                    <span class="sr-text-green">Understand the investment.</span>
                @endif
            </h1>

            <p class="sr-hero-desc">
                {{ $heroSection->description ?? 'Find deep Australian market research and fundamental analysis before you invest. Backed by institutional-grade valuation models and expert perspectives.' }}
            </p>

            <div class="sr-hero-actions">
                <a href="{{ route('register') }}" class="btn-green">
                    Start Free Trial
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="{{ route('research') }}" class="btn-outline-white">Explore Research</a>
            </div>

            <div class="sr-hero-trust-row">
                <span class="sr-trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    7-Day Free Trial
                </span>
                <span class="sr-trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    No Credit Card Required
                </span>
                <span class="sr-trust-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Instant Access
                </span>
            </div>
        </div>

        <!-- Right Visual Mockup (Multi-Device Terminal UI) -->
        <div class="sr-hero-mockup-wrap">
            <div class="sr-mockup-glow"></div>
            
            <!-- Desktop / Tablet Dashboard Mockup -->
            <div class="sr-device-screen">
                <div class="sr-screen-header">
                    <div class="sr-screen-dots">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="sr-screen-url">SharesRise research workspace</div>
                    <span class="sr-screen-badge">DEMO DATA</span>
                </div>

                <div class="sr-screen-body">
                    <!-- Top metrics row -->
                    <div class="sr-dash-metrics">
                        <div class="sr-dash-metric">
                            <small>S&P/ASX 200</small>
                            <strong>7,812.60</strong>
                            <span class="pos">+1.28% (98.75)</span>
                        </div>
                        <div class="sr-dash-metric">
                            <small>BHP Group</small>
                            <strong>$42.85</strong>
                            <span class="pos">+2.14%</span>
                        </div>
                        <div class="sr-dash-metric">
                            <small>Valuation Model</small>
                            <strong style="color: var(--green-400);">UNDERVALUED</strong>
                            <span class="sr-fair-val">Target: $48.50</span>
                        </div>
                    </div>

                    <!-- Interactive Chart Preview -->
                    <div class="sr-dash-chart">
                        <div class="sr-chart-header">
                            <span>ASX: BHP — Valuation vs Market Price</span>
                            <span class="sr-chart-legend">
                                <i class="dot-green"></i> Target
                                <i class="dot-blue"></i> Price
                            </span>
                        </div>
                        <svg viewBox="0 0 400 130" class="sr-svg-chart" aria-hidden="true">
                            <defs>
                                <linearGradient id="heroGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.4"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <!-- Grid lines -->
                            <line x1="0" y1="30" x2="400" y2="30" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                            <line x1="0" y1="70" x2="400" y2="70" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                            <line x1="0" y1="110" x2="400" y2="110" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                            
                            <!-- Area & Line -->
                            <path d="M0,105 Q50,90 100,95 T200,60 T300,45 T400,20 L400,130 L0,130 Z" fill="url(#heroGrad)"/>
                            <path d="M0,105 Q50,90 100,95 T200,60 T300,45 T400,20" stroke="#10b981" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                            <!-- Candlesticks sample points -->
                            <circle cx="200" cy="60" r="4" fill="#10b981" stroke="#ffffff" stroke-width="1.5"/>
                            <circle cx="400" cy="20" r="4" fill="#34d399" stroke="#ffffff" stroke-width="1.5"/>
                        </svg>
                    </div>

                    <!-- Mini Orderbook / Highlights -->
                    <div class="sr-dash-footer-row">
                        <span class="sr-rec-tag buy">CONVICTION: BUY</span>
                        <span class="sr-rec-stat">Intrinsic Margin of Safety: <strong>16.2%</strong></span>
                        <span class="sr-rec-stat">ROE: <strong>24.8%</strong></span>
                    </div>
                </div>
            </div>

            <!-- Overlapping Mobile Phone Mockup -->
            <div class="sr-phone-screen">
                <div class="sr-phone-header">
                    <small>Top ASX Pick</small>
                    <strong>CSL Limited (CSL)</strong>
                </div>
                <div class="sr-phone-badge-row">
                    <span class="tag-buy">BUY</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. DUAL LIVE MARKET & STOCK RECOMMENDATIONS SHOWCASE
     ========================================================================= -->
<section class="sr-section sr-market-showcase-section">
    <div class="container">
        <div class="sr-dual-market-grid">
            <!-- Left: Live ASX Market (Dark Navy Panel) -->
            @if($marketSection)
            <div class="sr-market-dark-panel">
                <div class="sr-panel-header">
                    <div>
                        <div class="sr-panel-tag">{{ $marketSection->eyebrow ?: 'MARKET SNAPSHOT' }}</div>
                        <h3 class="text-white">{{ $marketSection->title ?: 'ASX market snapshot' }}</h3>
                        @if($marketSection->description)
                            <p>{{ $marketSection->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="sr-table-responsive">
                    <table class="sr-dark-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Code</th>
                                <th>Price</th>
                                <th>Day High</th>
                                <th>Day Low</th>
                                <th class="text-right">Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($homepageStocks->take($marketSection->item_limit) as $company)
                                <tr>
                                    <td data-label="Company"><strong>{{ $company->name }}</strong><small>{{ $company->sector }}</small></td>
                                    <td data-label="Code"><span class="sr-code-badge">{{ $company->symbol }}</span></td>
                                    <td data-label="Price" class="font-mono">${{ number_format($company->price, 2) }}</td>
                                    <td data-label="Day high" class="font-mono text-muted">${{ number_format($company->day_high ?? $company->price, 2) }}</td>
                                    <td data-label="Day low" class="font-mono text-muted">${{ number_format($company->day_low ?? $company->price, 2) }}</td>
                                    <td data-label="Change" class="text-right">
                                        <span class="{{ $company->change >= 0 ? 'sr-pill-pos' : 'sr-pill-neg' }}">{{ $company->change >= 0 ? '+' : '' }}{{ number_format($company->change, 2) }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No companies are selected for the homepage.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Right: Past Recommendations & Research Coverage (Clean White Card) -->
            @if($researchSection)
            <div class="sr-market-light-panel">
                <div class="sr-panel-header">
                    <div>
                        <h3>{{ $researchSection->title }}</h3>
                        <p>{{ $researchSection->description }}</p>
                    </div>
                    <a href="{{ url($researchSection->button_url ?: route('research')) }}" class="sr-link-arrow">{{ $researchSection->button_label ?: 'View All →' }}</a>
                </div>

                <div class="sr-table-responsive">
                    <table class="sr-light-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Buy Price</th>
                                <th>Target</th>
                                <th>Return</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($homepageStocks->take($researchSection->item_limit) as $company)
                                @php
                                    $buyPrice = (float) ($company->buy_price ?? $company->price);
                                    $targetPrice = (float) ($company->target_price ?? $company->price);
                                    $targetReturn = $buyPrice > 0 ? (($targetPrice - $buyPrice) / $buyPrice) * 100 : 0;
                                @endphp
                                <tr>
                                    <td data-label="Company">
                                        <div class="sr-stock-cell">
                                            <span class="sr-ticker-bubble">{{ $company->symbol }}</span>
                                            <div>
                                                <b>{{ $company->name }}</b>
                                                <small>{{ $company->sector }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Buy price" class="font-mono">${{ number_format($buyPrice, 2) }}</td>
                                    <td data-label="Target" class="font-mono">${{ number_format($targetPrice, 2) }}</td>
                                    <td data-label="Return">
                                        <strong class="{{ $targetReturn >= 0 ? 'sr-text-green' : 'sr-text-red' }}">{{ $targetReturn >= 0 ? '+' : '' }}{{ number_format($targetReturn, 1) }}%</strong>
                                    </td>
                                    <td data-label="Status">
                                        <span class="sr-status-chip {{ $company->recommendation_status === 'Active' ? 'active' : 'achieved' }}">
                                            {{ $company->recommendation_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No companies are selected for the homepage.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="sr-disclaimer-foot">
                    <small>Disclaimer: Past performance is not an indicator of future performance. All valuations and recommendations represent general advice only.</small>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- =========================================================================
     4. ASX SECTOR INSIGHTS BANNER (Vibrant Cyan / Emerald Strip)
     ========================================================================= -->
<section class="sr-sector-banner-section">
    <div class="container">
        <div class="sr-sector-banner-inner">
            <h2 class="sr-sector-banner-title">
                Research by ASX Sector
            </h2>
            <p class="sr-sector-banner-sub">
                Browse company research across mining, banking, technology, healthcare, energy, and other major sectors.
            </p>

            <div class="sr-sector-pills-row">
                @php
                    $sectors = [
                        ['name' => 'Healthcare', 'bg' => '#f43f5e', 'svg' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>'],
                        ['name' => 'Mining', 'bg' => '#eab308', 'svg' => '<path d="m14 12-8.5 8.5a2.12 2.12 0 1 1-3-3L11 9"/><path d="M15 13 9 7l4-4 6 6-4 4Z"/><path d="m18 6 3-3"/>'],
                        ['name' => 'Banks', 'bg' => '#3b82f6', 'svg' => '<line x1="3" y1="21" x2="21" y2="21"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="3 10 12 4 21 10"/><line x1="6" y1="10" x2="6" y2="21"/><line x1="10" y1="10" x2="10" y2="21"/><line x1="14" y1="10" x2="14" y2="21"/><line x1="18" y1="10" x2="18" y2="21"/>'],
                        ['name' => 'Technology', 'bg' => '#8b5cf6', 'svg' => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>'],
                        ['name' => 'Energy', 'bg' => '#f97316', 'svg' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
                        ['name' => 'Materials', 'bg' => '#10b981', 'svg' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>'],
                        ['name' => 'Property', 'bg' => '#06b6d4', 'svg' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>'],
                        ['name' => 'Industrials', 'bg' => '#64748b', 'svg' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
                        ['name' => 'Retail', 'bg' => '#ec4899', 'svg' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'],
                        ['name' => 'Telecom', 'bg' => '#14b8a6', 'svg' => '<path d="M4.93 19.07A10 10 0 0 1 2 12a10 10 0 0 1 2.93-7.07"/><path d="M19.07 4.93A10 10 0 0 1 22 12a10 10 0 0 1-2.93 7.07"/><path d="M7.76 16.24A6 6 0 0 1 6 12a6 6 0 0 1 1.76-4.24"/><path d="M16.24 7.76A6 6 0 0 1 18 12a6 6 0 0 1-1.76 4.24"/><circle cx="12" cy="12" r="2"/>'],
                        ['name' => 'Agriculture', 'bg' => '#84cc16', 'svg' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
                    ];
                @endphp

                @foreach($sectors as $sec)
                    <a href="{{ route('research', ['sector' => $sec['name']]) }}" class="sr-sector-bubble" title="{{ $sec['name'] }}">
                        <div class="sr-sector-icon-circle" style="background: {{ $sec['bg'] }};">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                {!! $sec['svg'] !!}
                            </svg>
                        </div>
                        <span class="sr-sector-name">{{ $sec['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. SHAERSRISE REPORTS CATEGORIES (4 Photo Cards)
     ========================================================================= -->
<section class="sr-section sr-categories-section">
    <div class="container">
        <div class="sr-carousel-heading">
            <div class="sr-carousel-heading-copy">
                <h2>Research Collections</h2>
                <p>Browse reports by research style and investment objective.</p>
            </div>
            <div class="sr-carousel-controls" aria-label="Research collection controls">
                <button class="sr-nav-arrow prev" type="button" aria-label="Previous research collection" data-category-prev>&larr;</button>
                <button class="sr-nav-arrow next" type="button" aria-label="Next research collection" data-category-next>&rarr;</button>
            </div>
        </div>

        <div class="sr-category-cards-grid" data-category-carousel>
            @forelse($collections as $collection)
                @php
                    $fallbackImages = ['city.jpg', 'mining.jpg', 'energy.jpg', 'city.jpg'];
                    $cardTitle = $collection->display_name ?: $collection->name;
                    $imageUrl = $collection->image_path
                        ? route('media.file', basename($collection->image_path))
                        : asset('images/'.$fallbackImages[$loop->index % count($fallbackImages)]);
                @endphp
                <a href="{{ route('research', ['category' => $collection->name]) }}" class="sr-cat-photo-card">
                    <img src="{{ $imageUrl }}" alt="{{ $collection->image_alt ?: $cardTitle }}" loading="lazy">
                    <div class="sr-cat-overlay">
                        @if($collection->label)
                            <span class="sr-cat-pill-tag">{{ $collection->label }}</span>
                        @endif
                        <h3>{{ $cardTitle }}</h3>
                        <p>{{ $collection->description }}</p>
                        <span class="sr-cat-link">Explore Reports →</span>
                    </div>
                </a>
            @empty
                <p class="empty">Research collections will appear here when they are published.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- =========================================================================
     6. EXCLUSIVE RESEARCH CALL TO ACTION
     ========================================================================= -->
<section class="sr-research-cta-section">
    <div class="container">
        <div class="sr-research-cta">
            <span class="sr-research-cta-eyebrow">EXCLUSIVE STOCK RESEARCH</span>
            <h2>Discover High-Potential Investment Opportunities.</h2>
            <p>Explore high-potential ASX sectors with in-depth research reports and expert market insights to make more informed investment decisions.</p>
            <a class="btn-green sr-research-cta-button" href="{{ route('sample') }}">Download Your Sample Report Now <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</section>

<!-- =========================================================================
     7. LATEST ASX MARKET NEWS & INSIGHTS (Editorial Cards)
     ========================================================================= -->
<section class="sr-section sr-news-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <h2>Market Insights</h2>
            <p>Read the latest perspectives from the research desk.</p>
            <div class="sr-all-insights-link">
                <a href="{{ route('editorial') }}" class="sr-link-arrow">All Insights &amp; Market Perspectives →</a>
            </div>
        </div>

        <div class="sr-news-grid">
            @forelse($articles as $article)
                <article class="sr-news-card">
                    <a href="{{ route('article', $article->slug) }}" class="sr-news-thumb">
                        <img src="{{ asset('images/' . ($article->image ?: 'city') . '.jpg') }}" alt="{{ $article->title }}" loading="lazy">
                        <span class="sr-news-badge">{{ $article->topic }}</span>
                    </a>
                    <div class="sr-news-content">
                        <div class="sr-news-date">{{ $article->created_at->format('M d, Y') }} • 5 min read</div>
                        <h3 class="sr-news-title">
                            <a href="{{ route('article', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="sr-news-excerpt">{{ Str::limit($article->summary, 120) }}</p>
                        <a href="{{ route('article', $article->slug) }}" class="sr-news-readmore">
                            Read More →
                        </a>
                    </div>
                </article>
            @empty
                <p style="grid-column: 1 / -1; text-align: center; color: var(--slate-400);">No articles published yet.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- =========================================================================
     8. PRICING SECTION ("Choose the Plan That Boosts Your Portfolio")
     ========================================================================= -->
<section class="sr-pricing-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <h2>Membership options</h2>
            <p>Compare access levels and choose the plan that fits how you use the research library.</p>
            <div class="billing-toggle home-billing-toggle">
                <button class="selected" type="button" data-billing="monthly">Monthly</button>
                <button type="button" data-billing="yearly">Annual billing</button>
            </div>
        </div>

        <div class="pricing-grid home-pricing-grid">
            @forelse($plans as $plan)
                <article class="price-card {{ $plan->featured ? 'featured' : '' }}">
                    @if($plan->featured)
                        <span class="popular">FEATURED MEMBERSHIP</span>
                    @endif

                    <span class="eyebrow">{{ $plan->name }}</span>
                    <h2>{{ $plan->headline }}</h2>

                    <div class="plan-price">
                        <span data-monthly="{{ $plan->monthly_price }}" data-yearly="{{ $plan->yearly_price }}" data-trial="{{ $plan->is_trial ? '1' : '0' }}">${{ number_format($plan->monthly_price, 2) }}</span>
                        <small>{{ $plan->is_trial ? '/ 7-day trial' : '/ month' }}</small>
                    </div>
                    <p>{{ $plan->description }}</p>

                    <ul class="feature-list">
                        @foreach(preg_split('/\r?\n/', $plan->features) as $feature)
                            @if(trim($feature))
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    {{ ltrim(trim($feature), '✓ ') }}
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    @if($plan->is_trial)
                        <a class="button outline full" href="{{ route('register') }}">Start free trial <span aria-hidden="true">→</span></a>
                    @else
                        @auth
                            <form action="{{ route('subscribe') }}" method="post">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan->name }}">
                                <input class="billing-input" type="hidden" name="billing" value="monthly">
                                <button class="button full">Request {{ $plan->name }} <span aria-hidden="true">→</span></button>
                            </form>
                        @else
                            <a class="button full" href="{{ route('register') }}">Create an account <span aria-hidden="true">→</span></a>
                        @endauth
                    @endif
                </article>
            @empty
                <p class="centered" style="grid-column: 1 / -1;">No membership plans are currently published.</p>
            @endforelse
        </div>

    </div>
</section>

@if($testimonials->isNotEmpty())
<section class="sr-testimonials-section">
    <div class="container">
        <div class="sr-carousel-heading">
            <div class="sr-carousel-heading-copy">
                <span class="eyebrow">{{ $testimonialSection?->eyebrow ?: 'INVESTOR EXPERIENCES' }}</span>
                <h2>{{ $testimonialSection?->title ?: 'Trusted by thoughtful investors.' }}</h2>
                <p>{{ $testimonialSection?->description ?: 'Read experiences shared by the SharesRise community.' }}</p>
            </div>
            <div class="sr-carousel-controls" aria-label="Testimonial controls">
                <button class="sr-nav-arrow prev" type="button" aria-label="Previous testimonial" data-testimonial-prev>&larr;</button>
                <button class="sr-nav-arrow next" type="button" aria-label="Next testimonial" data-testimonial-next>&rarr;</button>
            </div>
        </div>
        <div class="sr-testimonials-grid" data-testimonials-carousel tabindex="0" aria-label="Client testimonials">
            @foreach($testimonials as $testimonial)
                <article class="sr-testimonial-card">
                    <p class="sr-testimonial-quote">“{{ $testimonial->quote }}”</p>
                    <div class="sr-reviewer-wrap">
                        <div class="sr-reviewer-avatar-box a{{ ($loop->index % 3) + 1 }}">{{ $testimonial->avatar_initials ?: strtoupper(substr($testimonial->name, 0, 2)) }}</div>
                        <div class="sr-reviewer-meta">
                            <b>{{ $testimonial->name }}</b>
                            <small>{{ $testimonial->role }}</small>
                            <div class="sr-stars-row" aria-label="{{ $testimonial->rating }} out of 5 stars">{{ str_repeat('★', min(5, max(1, $testimonial->rating))) }}</div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@endif

<!-- =========================================================================
     9. INSTANT FREE ASX REPORT LEAD GENERATION BANNER
     ========================================================================= -->
<section class="container sr-lead-gen-section">
    <div class="sr-lead-gen-banner">
        <div class="sr-lead-gen-grid">
            <!-- Left Info -->
            <div class="sr-lead-left">
                <h2>Start with a free report</h2>
                <p class="sr-lead-desc">
                    Use a practical checklist to assess a business, its cash flow, valuation assumptions, and key risks.
                </p>

                <div class="sr-lead-perks">
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>A repeatable company research checklist</span>
                    </div>
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Questions for testing valuation assumptions and risk</span>
                    </div>
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Educational material with no payment required</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Card -->
            <div class="sr-lead-right-card">
                <h3>Get your free report</h3>
                <p>Enter your details to open the complimentary guide.</p>

                <form method="POST" action="{{ route('lead') }}" class="sr-lead-form">
                    @csrf
                    <input type="hidden" name="type" value="report">

                    <div class="sr-form-group">
                        <label for="lead_name" class="sr-only">Full Name</label>
                        <input id="lead_name" name="name" type="text" placeholder="Your full name" required>
                    </div>

                    <div class="sr-form-group">
                        <label for="lead_phone" class="sr-only">Phone Number</label>
                        <input id="lead_phone" name="phone" type="tel" placeholder="+61 400 000 000">
                    </div>

                    <div class="sr-form-group">
                        <label for="lead_email" class="sr-only">Email Address</label>
                        <input id="lead_email" name="email" type="email" placeholder="name@example.com" required>
                    </div>

                    <div class="sr-form-consent">
                        <label>
                            <input type="checkbox" name="consent" value="1" required checked>
                            <span>By providing your details, you agree to {{ config('stockedge.site.brand_name', 'SharesRise') }}'s <a href="{{ route('page', 'terms') }}">Terms &amp; Conditions</a>, <a href="{{ route('page', 'privacy') }}">Privacy Policy</a> &amp; <a href="{{ route('page', 'financial-services-guide') }}">Financial Services Guide</a> and to receive marketing offers.</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-yellow-action">
                        Send my free report
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     10. FREQUENTLY ASKED QUESTIONS (Accordion)
     ========================================================================= -->
<section class="sr-section sr-faq-section">
    <div class="container" style="max-width: 860px;">
        <div class="sr-section-heading-centered">
            <h2>Frequently asked questions</h2>
            <p>Practical information about the research library, accounts, and memberships.</p>
        </div>

        <div class="sr-faq-accordion">
            @forelse($faqs as $faq)
                <details class="sr-faq-item" {{ $loop->first ? 'open' : '' }}>
                    <summary class="sr-faq-question">
                        <span>{{ $faq->question }}</span>
                        <span class="sr-faq-icon" aria-hidden="true"></span>
                    </summary>
                    <div class="sr-faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </details>
            @empty
                <p style="text-align: center; color: var(--slate-400);">No FAQs published.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
