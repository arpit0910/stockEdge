@extends('layouts.admin')
@section('title', 'Website settings')
@section('content')
<div class="a-page-heading">
    <div>
        <span class="a-kicker">IDENTITY, SEO & COMMUNICATION</span>
        <h1>Website settings.</h1>
        <p>Control the shared brand, search metadata, social profiles and contact details used across the public website.</p>
    </div>
    <button form="settings-form" class="a-button"><x-admin-icon name="check"/>Save settings</button>
</div>

<div class="a-editor-layout">
    <form id="settings-form" method="post" action="{{ route('admin.settings.save') }}" data-dirty-form>
        @csrf
        @method('PUT')
        @foreach([
            'Brand & website' => ['brand_name' => 'Brand name', 'announcement' => 'Announcement bar', 'footer_description' => 'Footer description'],
            'Search & sharing' => ['meta_description' => 'Default search description', 'seo_title_suffix' => 'Browser title suffix', 'default_social_image' => 'Default social image URL or path'],
            'Newsletter' => ['newsletter_title' => 'Signup heading', 'newsletter_description' => 'Signup description'],
            'Contact details' => ['contact_email' => 'Public email', 'contact_phone' => 'Public phone', 'contact_address' => 'Office address'],
            'Social profiles' => ['social_facebook' => 'Facebook URL', 'social_x' => 'X URL', 'social_linkedin' => 'LinkedIn URL', 'social_youtube' => 'YouTube URL'],
        ] as $group => $fields)
            <section class="a-panel a-field-panel">
                <div class="a-panel-heading"><h2>{{ $group }}</h2></div>
                <div class="a-field-grid">
                    @foreach($fields as $field => $label)
                        @php($optional = str_starts_with($field, 'contact_') || str_starts_with($field, 'social_') || $field === 'default_social_image')
                        <div class="a-field wide">
                            <label for="{{ $field }}">
                                {{ $label }}
                                @if($optional)<span class="a-optional">Optional</span>@else<span class="a-required">*</span>@endif
                            </label>
                            <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $settings[$field] ?? '') }}" type="{{ $field === 'contact_email' ? 'email' : 'text' }}" @required(! $optional)>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
        <button class="a-button">Save website settings <x-admin-icon name="check"/></button>
    </form>

    <aside>
        <section class="a-notebook">
            <span class="a-kicker">ONE SOURCE OF TRUTH</span>
            <h3>Brand and discovery.</h3>
            <p>The brand name appears in the header, footer, browser titles and structured search data. Search defaults are used whenever a page or record has no custom SEO copy.</p>
            <p>Articles, research reports, companies and pages can override these defaults in their own editors.</p>
            <div class="a-rule"></div>
            <h3>Publishing workflow</h3>
            <p>Only published pages, articles and reports appear publicly or in the XML sitemap. Draft URLs return a not-found response.</p>
        </section>
    </aside>
</div>
@endsection
