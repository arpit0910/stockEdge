@extends('layouts.app')

@section('title', 'Contact Us — Let’s Talk About Smarter Investing | SharesRise')

@section('content')
<div class="sr-contact-page-wrap">
    <section class="sr-page-hero sr-contact-hero">
        <div class="container sr-contact-hero-container">
            <div class="sr-contact-hero-content">
                <div class="sr-hero-pill-badge">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>CONTACT SHARESRISE</span>
                </div>

                <h1 class="sr-page-hero-title sr-contact-title">Let's Talk About Smarter Investing</h1>

                <p class="sr-page-hero-desc sr-contact-subtitle">
                    Connect with our research team for plan guidance, stock research access and subscription support.
                </p>

                <div class="sr-contact-hero-points" aria-label="Support highlights">
                    <div>
                        <strong>Within 24 hours</strong>
                        <span>Typical response time</span>
                    </div>
                    <div>
                        <strong>ASX focused</strong>
                        <span>Research and plan support</span>
                    </div>
                    <div>
                        <strong>Clear guidance</strong>
                        <span>No unnecessary jargon</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. MAIN TWO-COLUMN SECTION: WHAT HAPPENS NEXT? vs CONTACT ENQUIRY FORM
         ========================================================================= -->
    <section class="container sr-contact-main-section">
        <div class="sr-contact-split-grid">
            <!-- ================= LEFT COLUMN: WHAT HAPPENS NEXT ================= -->
            <div class="sr-contact-left-col">
                <div class="sr-contact-left-intro">
                    <h2 class="sr-next-title">What Happens Next?</h2>
                    <p class="sr-next-desc">
                        We're here to help you get the most from SharesRise. Whether you have questions about our ASX research, subscriptions or need investment guidance, our team will point you in the right direction.
                    </p>
                </div>

                <!-- 2x2 Steps Grid -->
                <div class="sr-next-steps-grid">
                    <!-- Step 01 -->
                    <div class="sr-next-step-card">
                        <div class="sr-step-card-top">
                            <div class="sr-step-icon-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            </div>
                            <span class="sr-step-number" aria-hidden="true">01</span>
                        </div>
                        <h3 class="sr-step-title">Quick Response</h3>
                        <p class="sr-step-text">Expect a reply within 24 hours from our investor support team.</p>
                    </div>

                    <!-- Step 02 -->
                    <div class="sr-next-step-card">
                        <div class="sr-step-card-top">
                            <div class="sr-step-icon-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            </div>
                            <span class="sr-step-number" aria-hidden="true">02</span>
                        </div>
                        <h3 class="sr-step-title">Research Consultation</h3>
                        <p class="sr-step-text">Discuss our equity research plans, market coverage and investor needs.</p>
                    </div>

                    <!-- Step 03 -->
                    <div class="sr-next-step-card">
                        <div class="sr-step-card-top">
                            <div class="sr-step-icon-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <span class="sr-step-number" aria-hidden="true">03</span>
                        </div>
                        <h3 class="sr-step-title">Plan Guidance</h3>
                        <p class="sr-step-text">We help you choose the right research subscription based on your investing style.</p>
                    </div>

                    <!-- Step 04 -->
                    <div class="sr-next-step-card">
                        <div class="sr-step-card-top">
                            <div class="sr-step-icon-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                            </div>
                            <span class="sr-step-number" aria-hidden="true">04</span>
                        </div>
                        <h3 class="sr-step-title">Dedicated Support</h3>
                        <p class="sr-step-text">Get help with subscriptions, reports, billing and access to research tools.</p>
                    </div>
                </div>

                <!-- Australian Investors Stronger Together Tagline & Art -->
                <div class="sr-contact-art-quote">
                    <div class="sr-script-slogan">
                        <em>Australian Investors Stronger Together</em>
                    </div>
                    <div class="sr-sydney-skyline-wrap" aria-hidden="true">
                        <!-- Stylized Sydney Harbor Silhouette SVG -->
                        <svg width="220" height="42" viewBox="0 0 220 42" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.35">
                            <path d="M5,38 C25,38 35,28 48,28 C60,28 68,36 82,36 C92,36 100,20 114,20 C125,20 132,32 145,32 C155,32 165,24 175,24 C185,24 195,38 215,38"/>
                            <path d="M85,36 Q110,6 135,36"/>
                            <path d="M92,36 Q110,14 128,36"/>
                            <path d="M148,32 Q158,16 168,32"/>
                            <path d="M154,32 Q162,20 170,32"/>
                            <line x1="0" y1="40" x2="220" y2="40" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>

                <!-- Trust Badges Row -->
                <div class="sr-contact-trust-row">
                    <div class="sr-trust-item">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Independent Research</span>
                    </div>

                    <div class="sr-trust-item">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <span>ASX Focused Insights</span>
                    </div>

                    <div class="sr-trust-item">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>A Smarter Investing Community</span>
                    </div>
                </div>
            </div>

            <!-- ================= RIGHT COLUMN: INTERACTIVE FORM CARD ================= -->
            <div class="sr-contact-right-col">
                <div class="sr-enquiry-card">
                    <div class="sr-enquiry-heading">
                        <span class="eyebrow">SEND AN ENQUIRY</span>
                        <h2>How can we help?</h2>
                        <p>Tell us what you need and our team will direct your enquiry to the right person.</p>
                    </div>

                    @if (session('success'))
                        <div class="sr-contact-success-alert" role="alert">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <div>
                                <strong>Message Sent Successfully!</strong>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="post" action="{{ route('lead') }}" class="sr-enquiry-form">
                        @csrf
                        <input type="hidden" name="type" value="contact">

                        <!-- Row 1: Full name & Email address -->
                        <div class="sr-form-row-2">
                            <div class="sr-form-group">
                                <label class="sr-form-label" for="contact-name">
                                    Full name<span class="req">*</span>
                                </label>
                                <input 
                                    id="contact-name" 
                                    name="name" 
                                    type="text"
                                    class="sr-form-input @error('name') has-error @enderror" 
                                    placeholder="Enter your full name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    maxlength="120" 
                                    autocomplete="name"
                                />
                                @error('name')<span class="sr-field-err">{{ $message }}</span>@enderror
                            </div>

                            <div class="sr-form-group">
                                <label class="sr-form-label" for="contact-email">
                                    Email address<span class="req">*</span>
                                </label>
                                <input 
                                    id="contact-email" 
                                    name="email" 
                                    type="email"
                                    class="sr-form-input @error('email') has-error @enderror" 
                                    placeholder="Enter your email address" 
                                    value="{{ old('email') }}" 
                                    required 
                                    maxlength="200" 
                                    autocomplete="email"
                                />
                                @error('email')<span class="sr-field-err">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Row 2: Investor Type (Selectable Cards) -->
                        <div class="sr-form-group">
                            <label class="sr-form-label">
                                Investor type<span class="req">*</span>
                            </label>
                            <div class="sr-chip-selector-grid cols-4">
                                @php
                                    $currentInvestorType = old('investor_type', 'Retail Investor');
                                @endphp
                                <label class="sr-chip-option">
                                    <input type="radio" name="investor_type" value="Retail Investor" @checked($currentInvestorType === 'Retail Investor') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span>Retail Investor</span>
                                    </span>
                                </label>

                                <label class="sr-chip-option">
                                    <input type="radio" name="investor_type" value="Adviser" @checked($currentInvestorType === 'Adviser') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                        <span>Adviser</span>
                                    </span>
                                </label>

                                <label class="sr-chip-option">
                                    <input type="radio" name="investor_type" value="SMSF" @checked($currentInvestorType === 'SMSF') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M6 18V9"/><path d="M10 18V9"/><path d="M14 18V9"/><path d="M18 18V9"/><polygon points="12 2 2 7 22 7"/></svg>
                                        <span>SMSF</span>
                                    </span>
                                </label>

                                <label class="sr-chip-option">
                                    <input type="radio" name="investor_type" value="Business" @checked($currentInvestorType === 'Business') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="9" y1="6" x2="9" y2="6.01"/><line x1="15" y1="6" x2="15" y2="6.01"/><line x1="9" y1="10" x2="9" y2="10.01"/><line x1="15" y1="10" x2="15" y2="10.01"/><line x1="9" y1="14" x2="9" y2="14.01"/><line x1="15" y1="14" x2="15" y2="14.01"/><line x1="9" y1="18" x2="15" y2="18"/></svg>
                                        <span>Business</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Row 3: Market Focus (Selectable Cards) -->
                        <div class="sr-form-group">
                            <label class="sr-form-label">
                                Market focus<span class="req">*</span>
                            </label>
                            <div class="sr-chip-selector-grid cols-3">
                                @php
                                    $currentMarketFocus = old('market_focus', 'ASX Stocks');
                                @endphp
                                <label class="sr-chip-option">
                                    <input type="radio" name="market_focus" value="ASX Stocks" @checked($currentMarketFocus === 'ASX Stocks') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                        <span>ASX Stocks</span>
                                    </span>
                                </label>

                                <label class="sr-chip-option">
                                    <input type="radio" name="market_focus" value="ETFs" @checked($currentMarketFocus === 'ETFs') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                                        <span>ETFs</span>
                                    </span>
                                </label>

                                <label class="sr-chip-option">
                                    <input type="radio" name="market_focus" value="Research Plans" @checked($currentMarketFocus === 'Research Plans') />
                                    <span class="sr-chip-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                        <span>Research Plans</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Row 4: Mobile Number & Portfolio Size -->
                        <div class="sr-form-row-2">
                            <div class="sr-form-group">
                                <label class="sr-form-label" for="contact-phone">
                                    Mobile number
                                </label>
                                <div class="sr-phone-input-group">
                                    <div class="sr-country-prefix">
                                        <span class="sr-flag" aria-hidden="true">🇦🇺</span>
                                        <select name="country_code" class="sr-country-select" aria-label="Country Code">
                                            <option value="+61" selected>+61</option>
                                            <option value="+64">+64</option>
                                            <option value="+1">+1</option>
                                            <option value="+44">+44</option>
                                            <option value="+65">+65</option>
                                            <option value="+852">+852</option>
                                        </select>
                                        <svg class="sr-select-arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                    <input 
                                        id="contact-phone" 
                                        name="phone" 
                                        type="tel"
                                        class="sr-form-input phone-field @error('phone') has-error @enderror" 
                                        placeholder="Enter mobile number" 
                                        value="{{ old('phone') }}" 
                                        maxlength="30" 
                                        autocomplete="tel"
                                    />
                                </div>
                                @error('phone')<span class="sr-field-err">{{ $message }}</span>@enderror
                            </div>

                            <div class="sr-form-group">
                                <label class="sr-form-label" for="contact-portfolio">
                                    Portfolio size
                                </label>
                                <div class="sr-select-wrap">
                                    <select id="contact-portfolio" name="portfolio_size" class="sr-form-select">
                                        <option value="" disabled {{ old('portfolio_size') ? '' : 'selected' }}>Select portfolio size</option>
                                        <option value="Under $50,000" @selected(old('portfolio_size') === 'Under $50,000')>Under $50,000</option>
                                        <option value="$50,000 - $250,000" @selected(old('portfolio_size') === '$50,000 - $250,000')>$50,000 - $250,000</option>
                                        <option value="$250,000 - $1,000,000" @selected(old('portfolio_size') === '$250,000 - $1,000,000')>$250,000 - $1,000,000</option>
                                        <option value="$1,000,000+" @selected(old('portfolio_size') === '$1,000,000+')>$1,000,000+</option>
                                    </select>
                                    <svg class="sr-select-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Row 5: Message -->
                        <div class="sr-form-group">
                            <label class="sr-form-label" for="contact-message">
                                Message<span class="req">*</span>
                            </label>
                            <textarea 
                                id="contact-message" 
                                name="message" 
                                rows="4" 
                                class="sr-form-textarea @error('message') has-error @enderror" 
                                placeholder="Tell us about your research interests, investment goals or support request..." 
                                required 
                                maxlength="5000"
                            >{{ old('message') }}</textarea>
                            @error('message')<span class="sr-field-err">{{ $message }}</span>@enderror
                        </div>

                        <!-- Row 6: Consent Checkbox -->
                        <div class="sr-form-group">
                            <label class="sr-checkbox-label" for="contact-consent">
                                <input 
                                    id="contact-consent" 
                                    type="checkbox" 
                                    name="consent" 
                                    value="1" 
                                    required 
                                    @checked(old('consent', 1))
                                />
                                <span>
                                    I agree to the <a href="{{ route('page', 'privacy') }}" target="_blank">Privacy Policy</a> and consent to being contacted by SharesRise. <span class="req">*</span>
                                </span>
                            </label>
                            @error('consent')<span class="sr-field-err">{{ $message }}</span>@enderror
                        </div>

                        <!-- Row 7: Submit Button -->
                        <button type="submit" class="sr-contact-submit-btn">
                            <span>Send Enquiry</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
