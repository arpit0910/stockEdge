@extends('layouts.app')

@section('title', 'About SharesRise — Research Designed for Better Investment Decisions')

@section('content')
<!-- =========================================================================
     1. HERO HEADER
     ========================================================================= -->
<section class="sr-page-hero">
    <div class="container">
        <span class="sr-hero-pill-badge">
            <span class="dot"></span> ABOUT SHARESRISE
        </span>
        <h1 class="sr-page-hero-title">Research Designed for Better Investment Decisions</h1>
        <p class="sr-page-hero-desc">
            SharesRise is an Australian market research platform focused on helping investors understand opportunities across the ASX.
        </p>

        <div class="sr-about-hero-boxes">
            <div class="sr-about-hero-card">
                <div class="sr-about-hero-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <div>
                    <h4>Clear, Structured Research</h4>
                    <p>We provide structured research, company analysis, and market insights across blue-chip, mid-cap, small-cap, and emerging ASX companies.</p>
                </div>
            </div>

            <div class="sr-about-hero-card">
                <div class="sr-about-hero-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <div>
                    <h4>Business-First Philosophy</h4>
                    <p>Rather than focusing only on share-price movements, our research considers the underlying business, financial performance, industry conditions, valuation, market trends, and company-specific developments.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. WHY SHARESRISE?
     ========================================================================= -->
<section class="sr-section sr-about-why-section">
    <div class="container">
        <div class="sr-about-split-layout">
            <div class="sr-about-split-left">
                <span class="eyebrow">OUR VALUE PROPOSITION</span>
                <h2>Why SharesRise?</h2>
                <p class="lead-text">
                    The Australian share market offers thousands of investment opportunities across sectors, market capitalisations, and investment themes.
                </p>
                <p>
                    Identifying which companies deserve closer attention can be difficult when investors are faced with large volumes of announcements, financial reports, and market commentary. SharesRise is designed to make that process easier.
                </p>
                <p>
                    Our research brings together relevant company information, financial analysis, and market context so investors can better understand the businesses behind the ticker symbols.
                </p>

                <div class="sr-quote-box">
                    <blockquote>
                        “Our aim is not simply to tell investors what happened in the market, but to explain why it matters.”
                    </blockquote>
                </div>
            </div>

            <div class="sr-about-split-right">
                <div class="sr-pillars-card">
                    <h3>What We Focus on Delivering</h3>
                    <ul class="sr-pillars-list">
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Independent ASX Research</strong>
                                <p>Unbiased perspectives free from promotional bias or broker conflict.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Fundamental Company Analysis</strong>
                                <p>Deep dives into balance sheets, cash flow, profitability, and valuation metrics.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Market and Sector Insights</strong>
                                <p>Macro trends, thematic breakdowns, and sector-wide performance drivers.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Stock Ideas and Research Reports</strong>
                                <p>High-conviction investment cases with clear entry, target, and risk assumptions.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Small-Cap and Emerging Company Coverage</strong>
                                <p>Uncovering high-potential innovators and mineral explorers early.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>ETF and Thematic Investment Research</strong>
                                <p>Broad-market, commodity, and international ETFs for diversified portfolios.</p>
                            </div>
                        </li>
                        <li>
                            <span class="sr-pillar-bullet"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                            <div>
                                <strong>Clear Explanations Without Jargon</strong>
                                <p>Direct, actionable language designed for both everyday and experienced investors.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. OUR RESEARCH APPROACH
     ========================================================================= -->
<section class="sr-section sr-about-approach-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <span class="sr-hero-pill-badge" style="background: var(--green-50); border-color: var(--green-200); color: var(--green-700);">
                METHODOLOGY & FRAMEWORK
            </span>
            <h2 style="margin-top: 10px;">Our Research Approach</h2>
            <p>
                At SharesRise, investment research begins with understanding the business. Our research process considers both quantitative financial information and qualitative developments that may influence a company's future performance.
            </p>
        </div>

        <div class="sr-approach-grid">
            <!-- Card 1: Fundamental Analysis -->
            <div class="sr-approach-card">
                <div class="sr-approach-header">
                    <div class="sr-approach-badge green">01</div>
                    <div>
                        <h3>Fundamental Analysis</h3>
                        <p>We review core financial statements and qualitative operational factors:</p>
                    </div>
                </div>

                <div class="sr-tag-pill-cloud">
                    <span class="sr-topic-chip">Revenue & earnings trends</span>
                    <span class="sr-topic-chip">Balance sheet strength</span>
                    <span class="sr-topic-chip">Cash flow</span>
                    <span class="sr-topic-chip">Profitability</span>
                    <span class="sr-topic-chip">Debt levels</span>
                    <span class="sr-topic-chip">Capital structure</span>
                    <span class="sr-topic-chip">Company valuation</span>
                    <span class="sr-topic-chip">Competitive positioning</span>
                    <span class="sr-topic-chip">Management strategy</span>
                    <span class="sr-topic-chip">Industry outlook</span>
                </div>

                <div class="sr-approach-footer">
                    <p>This allows us to develop a clearer picture of a company's financial position and operating performance.</p>
                </div>
            </div>

            <!-- Card 2: Market & Technical Context -->
            <div class="sr-approach-card">
                <div class="sr-approach-header">
                    <div class="sr-approach-badge navy">02</div>
                    <div>
                        <h3>Market & Technical Context</h3>
                        <p>Company fundamentals are considered alongside broader market conditions:</p>
                    </div>
                </div>

                <div class="sr-tag-pill-cloud">
                    <span class="sr-topic-chip">Share-price trends</span>
                    <span class="sr-topic-chip">Trading momentum</span>
                    <span class="sr-topic-chip">Market sentiment</span>
                    <span class="sr-topic-chip">Support & resistance areas</span>
                    <span class="sr-topic-chip">Sector performance</span>
                    <span class="sr-topic-chip">Commodity movements</span>
                    <span class="sr-topic-chip">Macroeconomic developments</span>
                </div>

                <div class="sr-approach-footer">
                    <p>Combining company research with market context can provide investors with a more complete understanding of the factors influencing a stock.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     4. COVERING OPPORTUNITIES ACROSS THE ASX
     ========================================================================= -->
<section class="sr-section sr-about-coverage-section">
    <div class="container">
        <div class="sr-section-heading-centered">
            <span class="eyebrow">UNIVERSE & SCOPE</span>
            <h2>Covering Opportunities Across the ASX</h2>
            <p>SharesRise research covers companies across multiple market segments and sectors.</p>
        </div>

        <div class="sr-coverage-cards-grid">
            <!-- 1. Large-Cap -->
            <div class="sr-coverage-card">
                <div class="sr-cov-icon-wrap c-blue">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                </div>
                <h3>Large-Cap Companies</h3>
                <p>Established ASX businesses with larger market capitalisations, mature operations and significant market presence.</p>
                <a href="{{ route('research', ['cap' => 'Blue Chip']) }}" class="sr-cov-link">Explore Blue Chips →</a>
            </div>

            <!-- 2. Growth Companies -->
            <div class="sr-coverage-card">
                <div class="sr-cov-icon-wrap c-green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <h3>Growth Companies</h3>
                <p>Businesses expanding revenue, operations or addressable markets and pursuing longer-term growth opportunities.</p>
                <a href="{{ route('research', ['category' => 'Growth']) }}" class="sr-cov-link">Explore Growth →</a>
            </div>

            <!-- 3. Small-Cap & Penny Stocks -->
            <div class="sr-coverage-card">
                <div class="sr-cov-icon-wrap c-purple">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3>Small-Cap & Penny Stocks</h3>
                <p>Earlier-stage and smaller companies where exploration results, contracts, product development or operational milestones can materially influence valuations.</p>
                <a href="{{ route('research', ['cap' => 'Small Cap']) }}" class="sr-cov-link">Explore Small Caps →</a>
            </div>

            <!-- 4. Dividend Opportunities -->
            <div class="sr-coverage-card">
                <div class="sr-cov-icon-wrap c-emerald">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Dividend Opportunities</h3>
                <p>Companies that may appeal to investors researching income-generating investments and sustainable dividend strategies.</p>
                <a href="{{ route('research', ['category' => 'Dividend Investor']) }}" class="sr-cov-link">Explore Dividends →</a>
            </div>

            <!-- 5. ETFs -->
            <div class="sr-coverage-card">
                <div class="sr-cov-icon-wrap c-orange">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                </div>
                <h3>ETFs</h3>
                <p>Research covering ASX-listed exchange-traded funds across technology, commodities, income, international equities and other investment themes.</p>
                <a href="{{ route('research') }}" class="sr-cov-link">Explore ETFs →</a>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. BOTTOM CALL TO ACTION
     ========================================================================= -->
<section class="container sr-lead-gen-section" style="margin-bottom: 70px;">
    <div class="sr-lead-gen-banner" style="background: radial-gradient(circle at 90% 10%, rgba(16, 185, 129, 0.25) 0%, transparent 50%), var(--navy-900);">
        <div style="text-align: center; max-width: 720px; margin: 0 auto; color: #ffffff;">
            <span class="sr-hero-pill-badge" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3);">
                START YOUR RESEARCH JOURNEY
            </span>
            <h2 style="color: #ffffff; font-size: clamp(28px, 3.5vw, 40px); margin-top: 10px; margin-bottom: 14px;">
                Ready to make smarter, data-backed ASX investments?
            </h2>
            <p style="color: var(--slate-300); font-size: 16px; margin-bottom: 28px; line-height: 1.6;">
                Explore our full research library or begin your 7-day free trial with unrestricted access to our valuation models and stock reports.
            </p>
            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('register') }}" class="btn-green" style="padding: 14px 28px; font-size: 15px;">
                    Start 7-Day Free Trial →
                </a>
                <a href="{{ route('research') }}" class="btn-outline-white" style="padding: 14px 28px; font-size: 15px;">
                    Browse Research Library ↗
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

