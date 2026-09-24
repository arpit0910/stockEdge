<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $brandName = config('stockedge.site.brand_name', 'SharesRise');
        $titleSuffix = config('stockedge.site.seo_title_suffix') ?: $brandName;
        $contentTitle = isset($item) ? ($item->seo_title ?: $item->title) : (isset($stock) ? ($stock->seo_title ?: $stock->name.' (ASX: '.$stock->symbol.')') : (isset($pageContent) ? $pageContent->title : trim($__env->yieldContent('title', 'Australian Stock Market Research'))));
        $contentTitle = html_entity_decode($contentTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $fullTitle = $contentTitle === $titleSuffix ? $contentTitle : $contentTitle.' | '.$titleSuffix;
        $metaDescription = isset($item) ? ($item->meta_description ?: $item->summary) : (isset($stock) ? ($stock->meta_description ?: $stock->description) : (isset($pageContent) ? ($pageContent->meta_description ?: $pageContent->summary) : config('stockedge.site.meta_description', 'SharesRise delivers independent Australian equity research, ASX market insights and practical tools for informed investors.')));
        $metaDescription = Str::limit(trim(strip_tags($metaDescription)), 170, '');
        $canonicalUrl = url()->current();
        $defaultSocialImage = config('stockedge.site.default_social_image', '/images/city.jpg');
        $socialImage = isset($item) && $item instanceof \App\Models\Article && $item->image_path ? route('media.file', basename($item->image_path)) : (str_starts_with($defaultSocialImage, 'http') ? $defaultSocialImage : url($defaultSocialImage));
        $robots = request()->routeIs('login', 'register', 'password.*', 'dashboard', 'account', 'sample') || request()->filled('q') ? 'noindex, follow' : 'index, follow, max-image-preview:large';
        $socialProfiles = array_values(array_filter([config('stockedge.site.social_facebook'), config('stockedge.site.social_x'), config('stockedge.site.social_linkedin'), config('stockedge.site.social_youtube')]));
        $schemaGraph = [
            ['@type' => 'Organization', '@id' => url('/').'#organization', 'name' => $brandName, 'url' => url('/'), 'logo' => asset('images/sharesrise-logo.svg'), 'sameAs' => $socialProfiles],
            ['@type' => 'WebSite', '@id' => url('/').'#website', 'url' => url('/'), 'name' => $brandName, 'publisher' => ['@id' => url('/').'#organization'], 'potentialAction' => ['@type' => 'SearchAction', 'target' => route('research').'?q={search_term_string}', 'query-input' => 'required name=search_term_string']],
        ];
        if (isset($item)) {
            $schemaGraph[] = ['@type' => $kind === 'article' ? 'Article' : 'TechArticle', 'headline' => $item->title, 'description' => $metaDescription, 'url' => $canonicalUrl, 'mainEntityOfPage' => $canonicalUrl, 'datePublished' => $item->created_at->toIso8601String(), 'dateModified' => $item->updated_at->toIso8601String(), 'image' => $socialImage, 'author' => ['@id' => url('/').'#organization'], 'publisher' => ['@id' => url('/').'#organization']];
        } elseif (isset($stock)) {
            $schemaGraph[] = ['@type' => 'Corporation', 'name' => $stock->name, 'tickerSymbol' => 'ASX:'.$stock->symbol, 'description' => $metaDescription, 'url' => $canonicalUrl];
        }
        $structuredData = ['@context' => 'https://schema.org', '@graph' => $schemaGraph];
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:type" content="{{ isset($item) ? 'article' : 'website' }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $socialImage }}">
    @if(isset($item))
        <meta property="article:published_time" content="{{ $item->created_at->toIso8601String() }}">
        <meta property="article:modified_time" content="{{ $item->updated_at->toIso8601String() }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $socialImage }}">
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <!-- Top Bar (Centered Announcement) -->
    <div class="sr-topbar">
        <div class="container sr-topbar-inner">
            <span class="sr-topbar-title">{{ config('stockedge.site.announcement', 'Australia Stock Market Research Platform · Independent Research & High-Conviction ASX Analysis') }}</span>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="site-header">
        <div class="container nav-row">
            <a class="brand" href="{{ route('home') }}" aria-label="{{ $brandName }} home">
                <img class="brand-mark" src="{{ asset('images/sharesrise-mark.svg') }}" alt="" width="36" height="36">
                <span>{{ $brandName }}</span>
            </a>

            <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false" aria-controls="navigation">☰</button>

            <nav id="navigation" aria-label="Main navigation">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
                <a href="{{ route('page', 'pricing') }}" @class(['active' => request()->is('pricing')])>Subscribe</a>
                <a href="{{ route('research') }}" @class(['active' => request()->routeIs('research', 'report', 'stock')])>Research &amp; Report</a>
                <a href="{{ route('editorial') }}" @class(['active' => request()->routeIs('editorial', 'article')])>Editorial</a>
                <a href="{{ route('page', 'about') }}" @class(['active' => request()->is('about')])>About US</a>
                <a href="{{ route('page', 'contact') }}" @class(['active' => request()->is('contact')])>Contact</a>
            </nav>
        </div>
    </header>

    @unless(request()->routeIs('home'))
        <!-- Ticker Strip on inner pages -->
        <div class="market-strip">
            <div class="container market-inner">
                <span class="market-label"><span class="dot"></span> ASX COVERAGE SNAPSHOT</span>
                @foreach(config('stockedge.market_snapshot', []) as $stockSnapshot)
                    <div class="ticker">
                        <span>{{ $stockSnapshot->symbol }}</span>
                        <b>${{ number_format($stockSnapshot->price, 2) }}</b>
                        <em class="{{ $stockSnapshot->change >= 0 ? 'positive' : 'negative' }}">{{ $stockSnapshot->change >= 0 ? '+' : '' }}{{ number_format($stockSnapshot->change, 2) }}%</em>
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
                        <img class="brand-mark" src="{{ asset('images/sharesrise-mark.svg') }}" alt="" width="36" height="36">
                        <span>{{ $brandName }}</span>
                    </a>
                    <p class="sr-footer-brand-bio">
                        {{ config('stockedge.site.footer_description', 'Independent research. Expert insights. Smarter investments.') }}
                    </p>
                    <div class="sr-social-links">
                        <a href="{{ config('stockedge.site.social_facebook') ?: 'https://facebook.com' }}" target="_blank" rel="noopener" class="sr-social-icon" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="{{ config('stockedge.site.social_x') ?: 'https://x.com' }}" target="_blank" rel="noopener" class="sr-social-icon" aria-label="X Twitter">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="{{ config('stockedge.site.social_linkedin') ?: 'https://linkedin.com' }}" target="_blank" rel="noopener" class="sr-social-icon" aria-label="LinkedIn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                        <a href="{{ config('stockedge.site.social_youtube') ?: 'https://youtube.com' }}" target="_blank" rel="noopener" class="sr-social-icon" aria-label="YouTube">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
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
                    @forelse(config('stockedge.footer_pages', []) as $footerPage)
                        <a href="{{ route('page', $footerPage->slug) }}">{{ $footerPage->title }}</a>
                    @empty
                        <a href="{{ route('page', 'contact') }}">Contact Us</a>
                    @endforelse
                </div>

                <!-- Newsletter -->
                <div class="sr-footer-col">
                    <h4>{{ config('stockedge.site.newsletter_title', 'Newsletter') }}</h4>
                    <p class="sr-newsletter-desc">
                        {{ config('stockedge.site.newsletter_description', 'Research updates and market perspectives, in your inbox.') }}
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

                <nav class="sr-footer-mobile-links" aria-label="Footer navigation">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('page', 'pricing') }}">Subscribe</a>
                    <a href="{{ route('research') }}">Research</a>
                    <a href="{{ route('editorial') }}">Editorial</a>
                    <a href="{{ route('page', 'about') }}">About Us</a>
                    <a href="{{ route('page', 'contact') }}">Contact</a>
                </nav>
            </div>

            <!-- Footer Bottom -->
            <div class="sr-footer-bottom">
                <span>© {{ date('Y') }} {{ $brandName }}. All rights reserved.</span>
                <span>ABN 00 000 000 000 | AFSL 000000</span>
            </div>
        </div>
    </footer>
</body>
</html>
