@extends('layouts.app')

@section('title', 'Smarter Insights. Stronger Investments.')

@section('content')
<!-- =========================================================================
     1. HERO SECTION
     ========================================================================= -->
@php
    $heroSection = $siteSections->get('hero');
@endphp
<section class="sr-hero">
    <div class="container sr-hero-grid">
        <!-- Left Content -->
        <div class="sr-hero-content">
            @if($heroSection && $heroSection->title)
                <h1 class="sr-hero-title">
                    {!! str_contains($heroSection->title, 'Investments') ? str_replace('Investments', '<span class="sr-text-green">Investments</span>', e($heroSection->title)) : e($heroSection->title) !!}
                </h1>
                <p class="sr-hero-desc">
                    {{ $heroSection->description }}
                </p>
            @else
                <h1 class="sr-hero-title">
                    Smarter Insights.<br>
                    Stronger <span class="sr-text-green">Investments.</span>
                </h1>
                <p class="sr-hero-desc">
                    Independent research and expert analysis to help Australian investors make confident, data-backed decisions.
                </p>
            @endif

            <div class="sr-hero-badges">
                <span class="sr-badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    Expert Research
                </span>
                <span class="sr-badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    Actionable Ideas
                </span>
                <span class="sr-badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    Market Updates
                </span>
                <span class="sr-badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    Invest Smarter
                </span>
            </div>

            <div class="sr-hero-actions">
                <a href="{{ $heroSection?->button_url ? url($heroSection->button_url) : route('register') }}" class="btn-green">
                    {{ $heroSection?->button_label ?: 'Start 7-Day Free Trial' }}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="{{ route('research') }}" class="btn-outline-white">Explore Research</a>
            </div>

            <div class="sr-social-proof">
                <div class="sr-social-proof-label">Trusted by 50,000+ investors across Australia</div>
                <div class="sr-social-proof-row">
                    <div class="sr-avatar-stack">
                        <span class="sr-avatar-circle c1">MD</span>
                        <span class="sr-avatar-circle c2">SK</span>
                        <span class="sr-avatar-circle c3">JT</span>
                        <span class="sr-avatar-circle c4">AL</span>
                    </div>
                    <div class="sr-rating-block">
                        <div class="sr-stars-row">
                            @for($s = 0; $s < 5; $s++)
                                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                        <span class="sr-rating-text">4.8/5 from 1,200+ reviews</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Visual Card -->
        <div class="sr-hero-card">
            <img src="{{ asset('images/city.jpg') }}" alt="Sydney Harbour Skyline" class="sr-hero-card-img" loading="eager">
            <div class="sr-live-widget">
                <div class="sr-widget-top">
                    <span class="sr-widget-index">ASX 200</span>
                    <span class="sr-widget-change">+1.28% (98.75)</span>
                </div>
                <div class="sr-widget-val-row">
                    <span class="sr-widget-val">7,812.60</span>
                </div>
                <div class="sr-chart-container">
                    <svg viewBox="0 0 320 85" fill="none">
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,65 Q30,55 60,60 T120,40 T180,48 T240,20 T320,10 L320,85 L0,85 Z" fill="url(#chartGrad)"/>
                        <path d="M0,65 Q30,55 60,60 T120,40 T180,48 T240,20 T320,10" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="sr-widget-meta">
                    <span class="sr-status-dot"></span>
                    <span>Market Open • {{ now()->format('d M, Y h:i A') }} AEST</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. FEATURED IN BAR
     ========================================================================= -->
<section class="sr-featured-section">
    <div class="container sr-featured-inner">
        <span class="sr-featured-label">FEATURED IN</span>
        <div class="sr-featured-logos">
            <span class="sr-press-logo yahoo">yahoo!<em>finance</em></span>
            <span class="sr-press-logo the-australian">THE AUSTRALIAN</span>
            <span class="sr-press-logo afr">FINANCIAL REVIEW</span>
            <span class="sr-press-logo abc">000ABC</span>
            <span class="sr-press-logo smh">The Sydney Morning Herald</span>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. WHY AUSTRALIAN INVESTORS CHOOSE US (5 Feature Cards)
     ========================================================================= -->
<section class="sr-section sr-why-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <h2>Why Australian <span class="sr-text-green">Investors</span> Choose Us</h2>
        </div>

        <div class="sr-why-grid">
            <div class="sr-why-card">
                <div class="sr-why-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                </div>
                <h3>Expert-Led Research</h3>
                <p>Our team of analysts provides independent, well-researched insights.</p>
            </div>

            <div class="sr-why-card">
                <div class="sr-why-icon cyan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><polyline points="2 8 8 2 14 8 22 2"/></svg>
                </div>
                <h3>Actionable Stock Ideas</h3>
                <p>Timely stock picks and opportunities across ASX and global markets.</p>
            </div>

            <div class="sr-why-card">
                <div class="sr-why-icon emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                </div>
                <h3>Data-Backed Analysis</h3>
                <p>We combine data, fundamentals, and technical insights.</p>
            </div>

            <div class="sr-why-card">
                <div class="sr-why-icon sky">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Built for Australian Investors</h3>
                <p>Local market focus with global perspective.</p>
            </div>

            <div class="sr-why-card">
                <div class="sr-why-icon purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h3>Investor Education</h3>
                <p>Learn, grow, and improve your investing skills with expert content.</p>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     4. WHAT YOU GET SECTION (Dark Navy Container)
     ========================================================================= -->
<section class="container">
    <div class="sr-what-container">
        <h2>What You Get</h2>
        <div class="sr-what-grid">
            <div class="sr-what-card">
                <div class="sr-what-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h4>Daily Market Updates</h4>
                <p>Stay informed with daily market news and movement.</p>
            </div>

            <div class="sr-what-card">
                <div class="sr-what-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h4>Stock Research Reports</h4>
                <p>In-depth reports on ASX stocks and emerging opportunities.</p>
            </div>

            <div class="sr-what-card">
                <div class="sr-what-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                </div>
                <h4>Model Portfolios</h4>
                <p>Expert-curated portfolios for long-term wealth creation.</p>
            </div>

            <div class="sr-what-card">
                <div class="sr-what-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h4>Exclusive Insights</h4>
                <p>Access premium insights not available to the public.</p>
            </div>

            <div class="sr-what-card">
                <div class="sr-what-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                </div>
                <h4>Tools & Screeners</h4>
                <p>Powerful screening tools to find the best opportunities faster.</p>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. READY TO INVEST SMARTER? (Green Gradient Banner)
     ========================================================================= -->
@php
    $ctaSection = $siteSections->get('callout') ?? $siteSections->get('cta_banner');
@endphp
<section class="container sr-banner-wrap">
    <div class="sr-cta-banner">
        <div class="sr-cta-inner">
            <div class="sr-cta-flag-wrap">
                <!-- Australian Flag SVG Graphic -->
                <svg viewBox="0 0 60 40" class="sr-flag-graphic" aria-hidden="true">
                    <rect width="60" height="40" fill="#012169"/>
                    <rect width="30" height="20" fill="#00247d"/>
                    <path d="M0,0 L30,20 M30,0 L0,20" stroke="#fff" stroke-width="4"/>
                    <path d="M0,0 L30,20 M30,0 L0,20" stroke="#cc142b" stroke-width="2"/>
                    <path d="M15,0 L15,20 M0,10 L30,10" stroke="#fff" stroke-width="6"/>
                    <path d="M15,0 L15,20 M0,10 L30,10" stroke="#cc142b" stroke-width="3.5"/>
                    <polygon points="15,26 16.5,29 20,29 17,31 18,34.5 15,32.5 12,34.5 13,31 10,29 13.5,29" fill="#ffffff"/>
                    <circle cx="45" cy="8" r="1.5" fill="#fff"/>
                    <circle cx="52" cy="14" r="1.5" fill="#fff"/>
                    <circle cx="52" cy="26" r="1.5" fill="#fff"/>
                    <circle cx="45" cy="32" r="1.5" fill="#fff"/>
                    <circle cx="48" cy="21" r="1.2" fill="#fff"/>
                </svg>
            </div>

            <div class="sr-cta-text">
                <h3>{{ $ctaSection?->title ?: 'Ready to Invest Smarter?' }}</h3>
                <p>{{ $ctaSection?->description ?: 'Join thousands of Australian investors making better investment decisions every day.' }}</p>
                <a href="{{ $ctaSection?->button_url ? url($ctaSection->button_url) : route('register') }}" class="btn-green">
                    {{ $ctaSection?->button_label ?: 'Start Your 7-Day Free Trial' }}
                </a>
            </div>

            <div class="sr-cta-perks">
                <div class="sr-cta-perk-item">
                    <span class="sr-perk-icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </span>
                    <span>No Credit Card Required</span>
                </div>
                <div class="sr-cta-perk-item">
                    <span class="sr-perk-icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    </span>
                    <span>Cancel Anytime</span>
                </div>
                <div class="sr-cta-perk-item">
                    <span class="sr-perk-icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/></svg>
                    </span>
                    <span>7-Day Free Trial</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     6. KEY FEATURES & HIGHLIGHTS (4 Cards)
     ========================================================================= -->
<section class="sr-section sr-highlights-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <h2>Key Features & Highlights</h2>
        </div>

        <div class="sr-highlights-grid">
            <div class="sr-highlight-card">
                <div class="sr-highlight-icon-box green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <h3>Top Stock Picks</h3>
                <p>High-conviction ideas backed by deep research.</p>
            </div>

            <div class="sr-highlight-card">
                <div class="sr-highlight-icon-box emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <h3>Live Market Coverage</h3>
                <p>Real-time market updates and expert commentary.</p>
            </div>

            <div class="sr-highlight-card">
                <div class="sr-highlight-icon-box orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                </div>
                <h3>Sector Insights</h3>
                <p>Detailed analysis of key sectors driving the economy.</p>
            </div>

            <div class="sr-highlight-card">
                <div class="sr-highlight-icon-box purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <h3>Economic Updates</h3>
                <p>Stay ahead with the latest economic data and forecasts.</p>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     7. SIMPLE PRICING. EXCEPTIONAL VALUE. (Dynamic Plans from DB)
     ========================================================================= -->
<section class="sr-pricing-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <h2>Simple Pricing. Exceptional Value.</h2>
            <p>Choose the plan that suits your investing journey.</p>
        </div>

        <div class="sr-pricing-grid">
            @forelse($plans as $plan)
                <div class="sr-pricing-card {{ $plan->featured ? 'featured' : '' }}">
                    @if($plan->featured)
                        <span class="sr-popular-badge">MOST POPULAR</span>
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
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>7-Day Money-Back Guarantee. Cancel Anytime.</span>
        </div>
    </div>
</section>

<!-- =========================================================================
     8. TRUSTED BY INVESTORS AUSTRALIA-WIDE (Dynamic Testimonials from DB)
     ========================================================================= -->
<section class="sr-testimonials-section">
    <div class="container">
        <h2>Trusted by Investors Australia-Wide</h2>

        <div class="sr-testimonials-grid">
            @forelse($testimonials as $i => $t)
                <div class="sr-testimonial-card">
                    <p class="sr-testimonial-quote">
                        “{{ trim($t->quote, '“"”') }}”
                    </p>
                    <div class="sr-reviewer-wrap">
                        <div class="sr-reviewer-avatar-box a{{ ($i % 3) + 1 }}">
                            {{ $t->avatar_initials ?: strtoupper(substr($t->name, 0, 2)) }}
                        </div>
                        <div class="sr-reviewer-meta">
                            <b>— {{ $t->name }}</b>
                            <small>{{ $t->role }}</small>
                            <div class="sr-stars-row">
                                @for($star = 0; $star < min(5, max(1, $t->rating)); $star++)
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: var(--slate-400); grid-column: 1 / -1;">No testimonials published.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection