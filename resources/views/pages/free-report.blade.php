@extends('layouts.app')
@section('title', $pageContent->title)
@section('content')
<section class="container auth-layout">
    <div class="auth-story">
        <span class="eyebrow">{{ $pageContent->eyebrow }}</span>
        <h1>{{ $pageContent->title }}</h1>
        <p>{{ $pageContent->summary }}</p>
        @include('components.page-body')
    </div>
    <div class="form-panel">
        <h2>Get your free sample.</h2>
        <p>Original educational demo content. No stock tips or live recommendations.</p>
        <form method="post" action="{{ route('lead') }}">
            @csrf
            <input type="hidden" name="type" value="report">
            <label>Full name<input name="name" value="{{ old('name') }}" required></label>
            <label>Email address<input type="email" name="email" value="{{ old('email') }}" required></label>
            <label>Phone (optional)<input type="tel" name="phone" value="{{ old('phone') }}" maxlength="30"></label>
            <label class="consent"><input type="checkbox" name="consent" value="1" required> I agree to the <a href="{{ route('page', 'terms') }}">terms</a> and <a href="{{ route('page', 'privacy') }}">privacy policy</a>, and consent to follow-up about {{ config('stockedge.site.brand_name', 'SharesRise') }} research.</label>
            <button class="button full">Read my free report ↗</button>
        </form>
    </div>
</section>
@endsection
