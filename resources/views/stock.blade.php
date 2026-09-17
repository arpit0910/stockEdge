@extends('layouts.app')

@section('title', $stock->name . ' (' . $stock->symbol . ')')

@section('content')
<section class="sr-page-hero">
    <div class="container">
        <a class="back-link" href="{{ route('research') }}" style="color: var(--green-400); margin-bottom: 12px;">
            ← Back to Research Library
        </a>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <span class="sr-hero-pill-badge" style="margin-bottom: 0;">
                ASX: {{ $stock->symbol }}
            </span>
            <span style="font-size: 13px; color: var(--slate-300);">{{ $stock->sector }} · {{ $stock->cap }}</span>
        </div>
        <h1 class="sr-page-hero-title">{{ $stock->name }}</h1>
        <p class="sr-page-hero-desc">{{ $stock->description }}</p>
    </div>
</section>

<section class="container sr-library-section">
    <!-- Key Metrics Grid -->
    <div class="metric-grid">
        <div class="metric">
            <small>Current Price · AUD</small>
            <strong>${{ number_format($stock->price, 2) }}</strong>
        </div>
        <div class="metric">
            <small>24h Movement</small>
            <strong class="{{ $stock->change >= 0 ? 'positive' : 'negative' }}">
                {{ $stock->change >= 0 ? '+' : '' }}{{ $stock->change }}%
            </strong>
        </div>
        <div class="metric">
            <small>Dividend Yield</small>
            <strong style="color: var(--green-600);">{{ $stock->yield }}%</strong>
        </div>
        <div class="metric">
            <small>Market Capitalisation</small>
            <strong>{{ $stock->cap }}</strong>
        </div>
    </div>

    <div class="inline-actions" style="margin-bottom: 40px;">
        @auth
            <form action="{{ route('watch') }}" method="post">
                @csrf
                <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                <button class="btn-green">☆ Add to My Watchlist</button>
            </form>
        @else
            <a class="btn-green" href="{{ route('login') }}">Log in to Watchlist</a>
        @endauth
        <a class="button outline" href="{{ route('account', 'portfolio') }}">Add to Portfolio ↗</a>
    </div>

    <div class="sr-results-header">
        <div class="sr-results-count">
            <strong>Research & Reports</strong>
            <span>covering {{ $stock->symbol }}</span>
        </div>
    </div>

    <div class="reports-grid" style="margin-top: 24px;">
        @forelse($reports as $report)
            <x-report-card :report="$report"/>
        @empty
            <div class="sr-empty-results" style="grid-column: 1 / -1;">
                <div class="sr-empty-icon">📊</div>
                <h3>New research coverage for {{ $stock->symbol }} is being prepared</h3>
                <p>Our analysts publish weekly deep dives across ASX equities.</p>
                <a href="{{ route('research') }}" class="btn-green">Browse All Research →</a>
            </div>
        @endforelse
    </div>
</section>
@endsection