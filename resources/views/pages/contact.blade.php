@extends('layouts.app')

@section('title', $pageContent->title)

@section('content')
    <section class="contact-hero">
        <div class="container contact-hero-inner">
            <div class="contact-hero-copy">
                <span class="eyebrow">{{ $pageContent->eyebrow }}</span>
                <h1>{{ $pageContent->title }}</h1>
                <p>{{ $pageContent->summary }}</p>
            </div>

            <div class="contact-hero-note" aria-label="What to expect">
                <span class="contact-hero-note-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4 5.75A1.75 1.75 0 0 1 5.75 4h12.5A1.75 1.75 0 0 1 20 5.75v8.5A1.75 1.75 0 0 1 18.25 16H9l-5 4v-14.25Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="m7 8 5 3.5L17 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <div>
                    <strong>Start a conversation</strong>
                    <span>Share a few details and our team will respond directly.</span>
                </div>
            </div>
        </div>
    </section>

    <section class="container contact-section">
        <div class="contact-information">
            @include('components.page-body')

            <div class="contact-methods" aria-label="Contact details">
                @if(config('stockedge.site.contact_email'))
                    <a class="contact-method" href="mailto:{{ config('stockedge.site.contact_email') }}">
                        <span class="contact-method-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M4.5 6.5h15v11h-15z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" /><path d="m5 7 7 5 7-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </span>
                        <span><small>Email us</small><strong>{{ config('stockedge.site.contact_email') }}</strong></span>
                        <svg class="contact-method-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </a>
                @endif

                @if(config('stockedge.site.contact_phone'))
                    <a class="contact-method" href="tel:{{ preg_replace('/[^+\d]/', '', config('stockedge.site.contact_phone')) }}">
                        <span class="contact-method-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M8.2 4.5 10 8 8.1 9.5c1.2 2.6 3.2 4.6 5.8 5.8l1.5-1.9 3.5 1.8-.5 3.2c-.1.7-.8 1.2-1.5 1.1C10.3 18.8 5.2 13.7 4.5 7.1c-.1-.7.4-1.4 1.1-1.5l2.6-.4Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </span>
                        <span><small>Call us</small><strong>{{ config('stockedge.site.contact_phone') }}</strong></span>
                        <svg class="contact-method-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </a>
                @endif

                @if(config('stockedge.site.contact_address'))
                    <div class="contact-method contact-method-static">
                        <span class="contact-method-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M19 10c0 5-7 10-7 10S5 15 5 10a7 7 0 1 1 14 0Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" /><circle cx="12" cy="10" r="2.25" stroke="currentColor" stroke-width="1.7" /></svg>
                        </span>
                        <span><small>Based in</small><strong>{{ config('stockedge.site.contact_address') }}</strong></span>
                    </div>
                @endif
            </div>
        </div>

        <div class="contact-form-card">
            <div class="contact-form-heading">
                <span class="contact-form-kicker">Send an enquiry</span>
                <h2>How can we help?</h2>
                <p>Complete the form below and we’ll get back to you.</p>
            </div>

            <form method="post" action="{{ route('lead') }}" class="contact-form">
                @csrf
                <input type="hidden" name="type" value="contact">

                <div class="contact-form-grid">
                    <label for="contact-name">
                        <span>Your name <b aria-hidden="true">*</b></span>
                        <input id="contact-name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name" @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror>
                        @error('name')<small class="field-error" id="contact-name-error">{{ $message }}</small>@enderror
                    </label>

                    <label for="contact-email">
                        <span>Email address <b aria-hidden="true">*</b></span>
                        <input id="contact-email" type="email" name="email" value="{{ old('email') }}" required maxlength="200" autocomplete="email" inputmode="email" @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror>
                        @error('email')<small class="field-error" id="contact-email-error">{{ $message }}</small>@enderror
                    </label>
                </div>

                <label for="contact-phone">
                    <span>Phone number <em>Optional</em></span>
                    <input id="contact-phone" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel" inputmode="tel" @error('phone') aria-invalid="true" aria-describedby="contact-phone-error" @enderror>
                    @error('phone')<small class="field-error" id="contact-phone-error">{{ $message }}</small>@enderror
                </label>

                <label for="contact-message">
                    <span>How can we help? <b aria-hidden="true">*</b></span>
                    <textarea id="contact-message" name="message" rows="5" required maxlength="5000" placeholder="Tell us what you’d like to know…" @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror>{{ old('message') }}</textarea>
                    @error('message')<small class="field-error" id="contact-message-error">{{ $message }}</small>@enderror
                </label>

                <label class="contact-consent" for="contact-consent">
                    <input id="contact-consent" type="checkbox" name="consent" value="1" required @checked(old('consent')) @error('consent') aria-invalid="true" aria-describedby="contact-consent-error" @enderror>
                    <span>I consent to my details being used to respond to this enquiry, as described in the <a href="{{ route('page', 'privacy') }}">privacy policy</a>.</span>
                </label>
                @error('consent')<small class="field-error contact-consent-error" id="contact-consent-error">{{ $message }}</small>@enderror

                <button class="button full contact-submit" type="submit">
                    Send your message
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
                <p class="contact-form-footnote">Fields marked with an asterisk are required.</p>
            </form>
        </div>
    </section>
@endsection
