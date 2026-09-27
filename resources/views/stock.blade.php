@extends('layouts.app')

@section('title', $stock->name . ' (' . $stock->symbol . ')')

@section('content')
<section class="sr-page-hero internal-company-hero">
    <div class="container">
        <a class="back-link internal-hero-back" href="{{ route('research') }}">&larr; Back to research</a>
        <div class="internal-company-meta">
            <span class="sr-hero-pill-badge">ASX: {{ $stock->symbol }}</span>
            <span>{{ $stock->sector }}</span>
            <span>{{ $stock->cap }}</span>
        </div>
        <h1 class="sr-page-hero-title">{{ $stock->name }}</h1>
        <p class="sr-page-hero-desc">{{ $stock->description }}</p>
    </div>
</section>

<section class="container sr-library-section internal-company-page">
    <div class="internal-section-heading">
        <div>
            <span class="eyebrow">COMPANY SNAPSHOT</span>
            <h2>Key figures</h2>
        </div>
        <p>Illustrative market data for orientation only.</p>
    </div>

    <div class="metric-grid internal-metrics-grid">
        <div class="metric">
            <small>Current price</small>
            <strong>${{ number_format($stock->price, 2) }}</strong>
            <span>AUD</span>
        </div>
        <div class="metric">
            <small>24-hour movement</small>
            <strong class="{{ $stock->change >= 0 ? 'positive' : 'negative' }}">
                {{ $stock->change >= 0 ? '+' : '' }}{{ $stock->change }}%
            </strong>
            <span>Illustrative change</span>
        </div>
        <div class="metric">
            <small>Dividend yield</small>
            <strong class="positive">{{ $stock->yield }}%</strong>
            <span>Indicative yield</span>
        </div>
        <div class="metric">
            <small>Capitalisation</small>
            <strong>{{ $stock->cap }}</strong>
            <span>Market segment</span>
        </div>
    </div>

    <div class="inline-actions internal-company-actions">
        @auth
            <form action="{{ route('watch') }}" method="post">
                @csrf
                <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                <button class="btn-green">Add to watchlist</button>
            </form>
        @else
            <a class="btn-green" href="{{ route('login') }}">Log in to use watchlist</a>
        @endauth
        <a class="button outline" href="{{ route('account', 'portfolio') }}">Add to portfolio</a>
    </div>

    <div class="internal-section-heading internal-research-heading">
        <div>
            <span class="eyebrow">RELATED COVERAGE</span>
            <h2>Research on {{ $stock->symbol }}</h2>
        </div>
        <a class="text-link" href="{{ route('research') }}">View all research &rarr;</a>
    </div>

    <div class="reports-grid">
        @forelse($reports as $report)
            <x-report-card :report="$report"/>
        @empty
            <div class="sr-empty-results">
                <h3>New coverage is being prepared</h3>
                <p>Browse the research library while our next {{ $stock->symbol }} report is prepared.</p>
                <a href="{{ route('research') }}" class="btn-green">Browse all research &rarr;</a>
            </div>
        @endforelse
    </div>
</section>
@endsection
