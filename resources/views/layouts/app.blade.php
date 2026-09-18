<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Australia Stock Market Research Platform') | SharesRise</title>
    <meta name="description" content="SharesRise: Independent Australian equity research, market insights, and tools for confident, data-backed investing.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <!-- Top Bar -->
    <div class="sr-topbar">
        <div class="container sr-topbar-inner">
            <div class="sr-topbar-left">
                <span class="sr-topbar-title">AUSTRALIA STOCK MARKET RESEARCH PLATFORM 🇦🇺</span>
                <span class="sr-topbar-sub">Modern • Trustworthy • Data-Driven • Investor-Focused</span>
            </div>
            <div class="sr-topbar-right">
                <span class="sr-topbar-goals-label">DESIGN GOALS</span>
                <div class="sr-topbar-goals">
                    <span class="sr-goal-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Build Trust
                    </span>
                    <span class="sr-goal-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                        Easy to Navigate
                    </span>
                    <span class="sr-goal-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        Actionable Insights
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="site-header">
        <div class="container nav-row">
            <a class="brand" href="{{ route('home') }}" aria-label="SharesRise home">
                <span class="brand-bars-icon" aria-hidden="true">
                    <span></span><span></span><span></span><span></span>
                </span>
                <span>SharesRise</span>
            </a>

            <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false" aria-controls="navigation">☰</button>

            <nav id="navigation" aria-label="Main navigation">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>

                <details class="nav-dropdown">
                    <summary>Research <span>⌄</span></summary>
                    <div class="mega-menu">
                        <div>
                            <small>RESEARCH COLLECTIONS</small>
                            @foreach(config('stockedge.categories', []) as $category)
                                <a href="{{ route('research', ['category' => $category]) }}">{{ $category }}</a>
                            @endforeach
                        </div>
                        <div>
                            <small>EXPLORE THE MARKET</small>
                            <a href="{{ route('research') }}">All Research Library →</a>
                            <a href="{{ route('page', 'sectors') }}">Sector Insights</a>
                            @foreach(['Blue Chip', 'Mid Cap', 'Small Cap'] as $cap)
                                <a href="{{ route('research', ['cap' => $cap]) }}">{{ $cap }} Equities</a>
                            @endforeach
                            <a href="{{ route('page', 'performance') }}">Methodology & Record</a>
                        </div>
                    </div>
                </details>

                <a href="{{ route('research', ['category' => 'Stock of the Week']) }}">Stock Picks</a>

                <details class="nav-dropdown">
                    <summary>Markets <span>⌄</span></summary>
                    <div class="small-menu">
                        <a href="{{ route('page', 'sectors') }}">ASX Sectors Overview</a>
                        <a href="{{ route('research', ['cap' => 'Blue Chip']) }}">ASX 200 Leaders</a>
                        <a href="{{ route('research', ['cap' => 'Mid Cap']) }}">Mid Cap Opportunities</a>
                        <a href="{{ route('research', ['cap' => 'Small Cap']) }}">Small Cap Growth</a>
                    </div>
                </details>

                <details class="nav-dropdown">
                    <summary>Learn <span>⌄</span></summary>
                    <div class="small-menu">
                        <a href="{{ route('editorial') }}">Market Insights</a>
                        <a href="{{ route('page', 'free-report') }}">Free Research Guide</a>
                        <a href="{{ route('page', 'retirement') }}">Retirement Planner</a>
                        <a href="{{ route('page', 'pricing') }}">Membership Plans</a>
                    </div>
                </details>

                <a href="{{ route('page', 'about') }}" @class(['active' => request()->is('about')])>About Us</a>

                <div class="mobile-nav-actions">
                    @auth
                        <a class="button outline" href="{{ route('dashboard') }}">My Dashboard</a>
                    @else
                        <a class="button outline" href="{{ route('login') }}">Login</a>
                    @endauth
                    <a class="btn-green" href="{{ route('register') }}">Start Free Trial</a>
                </div>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('research') }}" class="nav-search-btn" title="Search Research" aria-label="Search Research">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </a>
                @auth
                    <a class="nav-login-link" href="{{ route('dashboard') }}">My Dashboard</a>
                @else
                    <a class="nav-login-link" href="{{ route('login') }}">Login</a>
                @endauth
                <a class="btn-green" href="{{ route('register') }}">Start Free Trial</a>
            </div>
        </div>
    </header>

    @unless(request()->routeIs('home'))
        <!-- Ticker Strip on inner pages -->
        <div class="market-strip">
            <div class="container market-inner">
                <span class="market-label"><span class="dot"></span> ASX MARKET SNAPSHOT <small>LIVE</small></span>
                @foreach([['ASX 200','7,812.60','+1.28%'],['ALL ORDS','8,049.20','+1.15%'],['S&P 500','5,864.67','+0.62%'],['AUD/USD','0.6685','+0.34%']] as [$index,$price,$change])
                    <div class="ticker">
                        <span>{{ $index }}</span>
                        <b>{{ $price }}</b>
                        <em>↗ {{ $change }}</em>
                    </div>
                @endforeach
            </div>
        </div>
    @endunless

    <main id="main">
        @if(session('success'))
            <div class="container">
                <div class="notice" role="status">{{ session('success') }}</div>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="container">
                <div class="notice error" role="alert">
                    <strong>Please check your details.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="sr-footer">
        <div class="container">
            <div class="sr-footer-grid">
                <!-- Brand Column -->
                <div class="sr-footer-brand">
                    <a class="brand" href="{{ route('home') }}">
                        <span class="brand-bars-icon" aria-hidden="true">
                            <span></span><span></span><span></span><span></span>
                        </span>
                        <span>SharesRise</span>
                    </a>
                    <p class="sr-footer-brand-bio">
                        Independent research. Expert insights. Smarter investments.
                    </p>
                    <div class="sr-social-links">
                        <a href="https://facebook.com" target="_blank" rel="noopener" class="sr-social-icon" aria-label="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener" class="sr-social-icon" aria-label="X Twitter">
                            <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener" class="sr-social-icon" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener" class="sr-social-icon" aria-label="YouTube">
                            <svg viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#040914"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="sr-footer-col">
                    <h4>Quick Links</h4>
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('research', ['category' => 'Stock of the Week']) }}">Stock Picks</a>
                    <a href="{{ route('research') }}">Research</a>
                    <a href="{{ route('page', 'sectors') }}">Markets</a>
                    <a href="{{ route('editorial') }}">Learn</a>
                    <a href="{{ route('page', 'about') }}">About Us</a>
                </div>

                <!-- Resources -->
                <div class="sr-footer-col">
                    <h4>Resources</h4>
                    <a href="{{ route('page', 'free-report') }}">Help Center</a>
                    <a href="{{ route('page', 'contact') }}">Contact Us</a>
                    <a href="{{ route('page', 'terms') }}">Terms & Conditions</a>
                    <a href="{{ route('page', 'privacy') }}">Privacy Policy</a>
                    <a href="{{ route('page', 'pricing') }}">Refund Policy</a>
                </div>

                <!-- Newsletter -->
                <div class="sr-footer-col">
                    <h4>Newsletter</h4>
                    <p class="sr-newsletter-desc">
                        Stay updated with our latest research and market insights.
                    </p>
                    <form method="post" action="{{ route('lead') }}" class="sr-newsletter-form">
                        @csrf
                        <input type="hidden" name="type" value="newsletter">
                        <input type="hidden" name="consent" value="1">
                        <div class="sr-newsletter-form-row">
                            <input type="email" name="email" class="sr-newsletter-input" placeholder="Enter your email" required>
                            <button type="submit" class="sr-newsletter-btn">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="sr-footer-bottom">
                <span>© {{ date('Y') }} SharesRise. All rights reserved.</span>
                <span>ABN 00 000 000 000 | AFSL 000000</span>
            </div>
        </div>
    </footer>
</body>
</html>
