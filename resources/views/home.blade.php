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
            <span class="sr-hero-pill-badge">
                <span class="dot"></span> {{ $heroSection->eyebrow ?? 'SPECIALIST EQUITY RESEARCH' }}
            </span>

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
                    <div class="sr-screen-url">sharesrise.com.au/terminal/asx200</div>
                    <span class="sr-screen-badge">LIVE AEST</span>
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
                <div class="sr-phone-notch"></div>
                <div class="sr-phone-header">
                    <small>Top ASX Pick</small>
                    <strong>CSL Limited (CSL)</strong>
                </div>
                <div class="sr-phone-badge-row">
                    <span class="tag-buy">BUY</span>
                    <span class="gain">+17.2% Return</span>
                </div>
                <div class="sr-phone-bar">
                    <div class="bar-fill" style="width: 78%;"></div>
                </div>
                <small class="sr-phone-note">Target: $310.00 • Low Risk</small>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. STATS & TRACK RECORD COUNTER STRIP (4 Cards)
     ========================================================================= -->
<section class="sr-stats-section">
    <div class="container">
        <div class="sr-stats-grid">
            <div class="sr-stat-card">
                <div class="sr-stat-number">84%+</div>
                <div class="sr-stat-label">High Conviction Success Rate</div>
                <p class="sr-stat-sub">Validated across market cycles</p>
            </div>
            <div class="sr-stat-card">
                <div class="sr-stat-number">1025+</div>
                <div class="sr-stat-label">ASX Companies Covered</div>
                <p class="sr-stat-sub">Fundamental valuation coverage</p>
            </div>
            <div class="sr-stat-card">
                <div class="sr-stat-number">15,483</div>
                <div class="sr-stat-label">Research Reports Read</div>
                <p class="sr-stat-sub">By Australian investors & SMSFs</p>
            </div>
            <div class="sr-stat-card">
                <div class="sr-stat-number">8+ years</div>
                <div class="sr-stat-label">Proven Market Track Record</div>
                <p class="sr-stat-sub">Independent equity analysis</p>
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
                        <div class="sr-panel-tag">{{ $marketSection->eyebrow ?? 'LIVE BENCHMARKS' }}</div>
                        <h3 class="text-white">{{ $marketSection->title ?? 'Live ASX Market Overview' }}</h3>
                    </div>
                    <div class="sr-live-badge">
                        <span class="sr-live-dot"></span> LIVE
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
                ASX Sector Insights Across Mining, Banking, Technology & More
            </h2>
            <p class="sr-sector-banner-sub">
                Explore fundamental research and valuation models across 11 key ASX sectors to find undervalued opportunities before the broader market.
            </p>

            <div class="sr-sector-pills-row">
                @php
                    $sectors = [
                        ['name' => 'Healthcare', 'icon' => '🏥', 'bg' => '#f43f5e'],
                        ['name' => 'Mining', 'icon' => '⛏️', 'bg' => '#eab308'],
                        ['name' => 'Banks', 'icon' => '🏦', 'bg' => '#3b82f6'],
                        ['name' => 'Technology', 'icon' => '💻', 'bg' => '#8b5cf6'],
                        ['name' => 'Energy', 'icon' => '⚡', 'bg' => '#f97316'],
                        ['name' => 'Materials', 'icon' => '🏗️', 'bg' => '#10b981'],
                        ['name' => 'Property', 'icon' => '🏢', 'bg' => '#06b6d4'],
                        ['name' => 'Industrials', 'icon' => '⚙️', 'bg' => '#64748b'],
                        ['name' => 'Retail', 'icon' => '🛍️', 'bg' => '#ec4899'],
                        ['name' => 'Telecom', 'icon' => '📡', 'bg' => '#14b8a6'],
                        ['name' => 'Agriculture', 'icon' => '🌾', 'bg' => '#84cc16'],
                    ];
                @endphp

                @foreach($sectors as $sec)
                    <a href="{{ route('research', ['sector' => $sec['name']]) }}" class="sr-sector-bubble" title="{{ $sec['name'] }}">
                        <div class="sr-sector-icon-circle" style="background: {{ $sec['bg'] }};">
                            <span>{{ $sec['icon'] }}</span>
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
        <div class="sr-section-heading">
            <div>
                <h2>SharesRise Reports Categories</h2>
                <p>Actionable research and recommendations for every kind of investor.</p>
            </div>
            <div class="sr-slider-nav">
                <button class="sr-nav-arrow prev" aria-label="Previous category" onclick="document.querySelector('.sr-category-cards-grid').scrollBy({left: -320, behavior: 'smooth'})">←</button>
                <button class="sr-nav-arrow next" aria-label="Next category" onclick="document.querySelector('.sr-category-cards-grid').scrollBy({left: 320, behavior: 'smooth'})">→</button>
            </div>
        </div>

        <div class="sr-category-cards-grid">
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
        <div class="sr-section-heading">
            <div>
                <h2>Latest ASX Market News, Stock Insights & Investment Trends</h2>
                <p>Stay informed with timely analysis and deep dives from our research desk.</p>
            </div>
            <a href="{{ route('editorial') }}" class="sr-link-arrow">All Insights →</a>
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
     7. CLIENT TESTIMONIALS & VIDEO REVIEW SHOWCASE (Dark Navy)
     ========================================================================= -->
<section class="sr-testimonials-dark-section">
    <div class="container">
        <div class="sr-section-heading-centered" style="margin-bottom: 35px;">
            <span class="sr-hero-pill-badge" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3);">
                <span class="dot"></span> CLIENT SUCCESS STORIES
            </span>
            <h2 style="color: #ffffff; font-size: 36px; margin-top: 10px;">What our clients say</h2>
            <p style="color: var(--slate-300); max-width: 680px; margin: 0 auto;">
                We deliver actionable research and recommendations that help Australian investors manage risk and achieve consistent returns across market cycles.
            </p>
        </div>

        <div class="sr-testimonial-video-grid">
            <!-- Left: Featured Testimonial Quote Card -->
            <div class="sr-testimonial-featured-card">
                <div class="sr-stars-row" style="color: #fbbf24; margin-bottom: 18px;">
                    @for($s = 0; $s < 5; $s++)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>
                <blockquote class="sr-featured-quote">
                    “SharesRise has transformed how I analyze Australian stocks. The valuation breakdowns and risk catalysts give me the conviction I need to hold winners and avoid high-multiple value traps. It paid for itself within the first month.”
                </blockquote>
                <div class="sr-reviewer-info">
                    <div class="sr-reviewer-avatar-box a1">DK</div>
                    <div>
                        <strong style="color: #ffffff; font-size: 15px; display: block;">David K.</strong>
                        <span style="color: var(--slate-400); font-size: 13px;">Self-Managed Super Fund (SMSF) Trustee • Sydney</span>
                    </div>
                </div>
            </div>

            <!-- Right: Video Review Card -->
            <div class="sr-video-review-card">
                <img src="{{ asset('images/city.jpg') }}" alt="Investor Case Study Video" class="sr-video-poster" loading="lazy">
                <div class="sr-video-overlay">
                    <div class="sr-play-btn" title="Play Video" aria-label="Play Video">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                    <div class="sr-video-meta">
                        <h4>Member Case Study: Building a $1.2M ASX Growth Portfolio</h4>
                        <span>Watch Video (3:45 mins)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Badges Bar -->
        <div class="sr-trust-aggregators">
            <div class="sr-aggregator-item">
                <strong>Clutch</strong>
                <span>4.9 / 5 Rating</span>
            </div>
            <div class="sr-aggregator-divider">•</div>
            <div class="sr-aggregator-item">
                <strong>Google Reviews</strong>
                <span>4.8 / 5 Rating</span>
            </div>
            <div class="sr-aggregator-divider">•</div>
            <div class="sr-aggregator-item">
                <strong>Trustpilot</strong>
                <span>Excellent ★★★★★</span>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     8. PRICING SECTION ("Choose the Plan That Boosts Your Portfolio")
     ========================================================================= -->
<section class="sr-pricing-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <span class="sr-hero-pill-badge" style="background: var(--green-50); border-color: var(--green-200); color: var(--green-700);">
                TRANSPARENT PRICING
            </span>
            <h2 style="margin-top: 10px;">Choose the Plan That Boosts Your Portfolio</h2>
            <p>Flexible plans for every stage of your investing journey.</p>
        </div>

        <div class="sr-pricing-grid">
            @forelse($plans as $plan)
                <div class="sr-pricing-card {{ $plan->featured ? 'featured' : '' }}">
                    @if($plan->featured)
                        <span class="sr-popular-badge">RECOMMENDED</span>
                    @endif

                    <div class="sr-plan-name">{{ $plan->name }}</div>
                    <div class="sr-plan-desc">{{ $plan->headline ?: $plan->description }}</div>

                    <div class="sr-plan-price-row">
                        <span class="sr-plan-price-currency">A$</span>
                        <span class="sr-plan-price-amount">{{ intval($plan->monthly_price) }}</span>
                        <span class="sr-plan-price-period">/month</span>
                    </div>

                    <ul class="sr-plan-features-list">
                        @foreach(preg_split('/\r?\n/', $plan->features) as $feature)
                            @if(trim($feature))
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    {{ ltrim(trim($feature), '✓ ') }}
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    @if($plan->featured)
                        <a href="{{ route('register') }}" class="btn-primary-green" style="width: 100%;">Start Free Trial</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-card-outline">Start Free Trial</a>
                    @endif
                </div>
            @empty
                <p class="centered" style="grid-column: 1 / -1;">No membership plans are currently published.</p>
            @endforelse
        </div>

        <div class="sr-guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>7-Day Money-Back Guarantee. Cancel Anytime with One Click.</span>
        </div>
    </div>
</section>

<!-- =========================================================================
     9. FREQUENTLY ASKED QUESTIONS (Accordion)
     ========================================================================= -->
<section class="sr-section sr-faq-section">
    <div class="container" style="max-width: 860px;">
        <div class="sr-section-heading-centered">
            <span class="sr-hero-pill-badge" style="background: var(--green-50); border-color: var(--green-200); color: var(--green-700);">
                FAQ
            </span>
            <h2 style="margin-top: 10px;">Frequently Asked Questions</h2>
            <p>Everything you need to know about our research platform and memberships.</p>
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
                <span class="sr-gold-badge">★ FREE DOWNLOAD</span>
                <h2>Get Your <span class="sr-text-yellow">Free ASX Report</span> Instantly</h2>
                <p class="sr-lead-desc">
                    Join thousands of Australian investors receiving our highest-conviction equity ideas and valuation models delivered straight to your inbox.
                </p>

                <div class="sr-lead-perks">
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Top 3 ASX stock picks with asymmetric upside</span>
                    </div>
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Comprehensive financial valuation models & risk analysis</span>
                    </div>
                    <div class="sr-lead-perk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>100% free with no commitment or credit card</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Card -->
            <div class="sr-lead-right-card">
                <h3>Get your free report</h3>
                <p>Enter your details below to download the latest PDF report.</p>

                <form method="POST" action="{{ route('lead') }}" class="sr-lead-form">
                    @csrf
                    <input type="hidden" name="type" value="report">

                    <div class="sr-form-group">
                        <label for="lead_name" class="sr-only">Full Name</label>
                        <input id="lead_name" name="name" type="text" placeholder="Your full name" required>
                    </div>

                    <div class="sr-form-group">
                        <label for="lead_phone" class="sr-only">Phone Number</label>
                        <input id="lead_phone" name="phone" type="tel" placeholder="+61 400 000 000 (optional)">
                    </div>

                    <div class="sr-form-group">
                        <label for="lead_email" class="sr-only">Email Address</label>
                        <input id="lead_email" name="email" type="email" placeholder="name@example.com" required>
                    </div>

                    <div class="sr-form-consent">
                        <label>
                            <input type="checkbox" name="consent" value="1" required checked>
                            <span>I agree to receive research updates. View our <a href="{{ route('page', 'privacy') }}">Privacy Policy</a>.</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-yellow-action">
                        Get My Free Report →
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
