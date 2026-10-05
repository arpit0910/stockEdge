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
                        <div class="sr-panel-tag">MARKET SNAPSHOT</div>
                        <h3 class="text-white">ASX market snapshot</h3>
                    </div>
                </div>

                <div class="sr-table-responsive">
                    <table class="sr-dark-table">
                        <thead>
                            <tr>
                                <th>Index Name</th>
                                <th>Code</th>
                                <th>Price</th>
                                <th>Day High</th>
                                <th>Day Low</th>
                                <th class="text-right">Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marketIndices as $idx)
                                <tr>
                                    <td><strong>{{ $idx['name'] }}</strong></td>
                                    <td><span class="sr-code-badge">{{ $idx['code'] }}</span></td>
                                    <td class="font-mono">{{ $idx['price'] }}</td>
                                    <td class="font-mono text-muted">{{ $idx['high'] }}</td>
                                    <td class="font-mono text-muted">{{ $idx['low'] }}</td>
                                    <td class="text-right">
                                        <span class="sr-pill-pos">{{ $idx['change'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
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
                            @foreach($pastRecommendations as $rec)
                                <tr>
                                    <td>
                                        <div class="sr-stock-cell">
                                            <span class="sr-ticker-bubble">{{ $rec['symbol'] }}</span>
                                            <div>
                                                <b>{{ $rec['name'] }}</b>
                                                <small>{{ $rec['sector'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-mono">${{ number_format($rec['buy'], 2) }}</td>
                                    <td class="font-mono">${{ number_format($rec['target'], 2) }}</td>
                                    <td>
                                        <strong class="sr-text-green">{{ $rec['return'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="sr-status-chip {{ $rec['active'] ? 'active' : 'achieved' }}">
                                            {{ $rec['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
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
        <div class="sr-section-heading-centered">
            <h2>Research Collections</h2>
            <p>Browse reports by research style and investment objective.</p>
            <div class="sr-slider-nav-centered" style="margin-top: 18px; display: inline-flex; gap: 10px;">
                <button class="sr-nav-arrow prev" aria-label="Previous category" onclick="document.querySelector('.sr-category-cards-grid').scrollBy({left: -320, behavior: 'smooth'})">←</button>
                <button class="sr-nav-arrow next" aria-label="Next category" onclick="document.querySelector('.sr-category-cards-grid').scrollBy({left: 320, behavior: 'smooth'})">→</button>
            </div>
        </div>

        <div class="sr-category-cards-grid" data-mobile-carousel>
            <!-- 1. Daily Recommendations -->
            <a href="{{ route('research', ['category' => 'Daily Analysis']) }}" class="sr-cat-photo-card">
                <img src="{{ asset('images/city.jpg') }}" alt="Daily Recommendations" loading="lazy">
                <div class="sr-cat-overlay">
                    <span class="sr-cat-pill-tag">DAILY DESK</span>
                    <h3>Daily Recommendations</h3>
                    <p>Timely updates and intraday catalyst analysis on active ASX stocks.</p>
                    <span class="sr-cat-link">Explore Reports →</span>
                </div>
            </a>

            <!-- 2. Dividend Report -->
            <a href="{{ route('research', ['category' => 'Dividend Investor']) }}" class="sr-cat-photo-card">
                <img src="{{ asset('images/mining.jpg') }}" alt="Dividend Report" loading="lazy">
                <div class="sr-cat-overlay">
                    <span class="sr-cat-pill-tag">INCOME INVESTING</span>
                    <h3>Dividend Report</h3>
                    <p>High-yield, fully franked dividend champions with robust free cash flow.</p>
                    <span class="sr-cat-link">Explore Reports →</span>
                </div>
            </a>

            <!-- 3. Resources & Energy Report -->
            <a href="{{ route('research', ['category' => 'Resources']) }}" class="sr-cat-photo-card">
                <img src="{{ asset('images/energy.jpg') }}" alt="Resources & Energy Report" loading="lazy">
                <div class="sr-cat-overlay">
                    <span class="sr-cat-pill-tag">COMMODITIES</span>
                    <h3>Resources & Energy</h3>
                    <p>Deep-dive research on critical minerals, copper, gold, and energy transition.</p>
                    <span class="sr-cat-link">Explore Reports →</span>
                </div>
            </a>

            <!-- 4. Growth Report -->
            <a href="{{ route('research', ['category' => 'Growth']) }}" class="sr-cat-photo-card">
                <img src="{{ asset('images/city.jpg') }}" alt="Growth Report" loading="lazy">
                <div class="sr-cat-overlay">
                    <span class="sr-cat-pill-tag">HIGH CONVICTION</span>
                    <h3>Growth Report</h3>
                    <p>High-upside mid and small-cap compounders with compounding earnings.</p>
                    <span class="sr-cat-link">Explore Reports →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- =========================================================================
     6. LATEST ASX MARKET NEWS & INSIGHTS (Editorial Cards)
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

<!-- =========================================================================
     9. FREQUENTLY ASKED QUESTIONS (Accordion)
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

<!-- =========================================================================
     10. INSTANT FREE ASX REPORT LEAD GENERATION BANNER
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
@endsection
