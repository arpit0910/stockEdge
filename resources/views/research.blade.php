@extends('layouts.app')

@section('title', 'Research & Reports')

@section('content')
<!-- Page Header -->
<section class="sr-page-hero">
    <div class="container">
        <span class="sr-hero-pill-badge">
            <span class="dot"></span> AUSTRALIA EQUITY RESEARCH DESK
        </span>
        <h1 class="sr-page-hero-title">ASX company research</h1>
        <p class="sr-page-hero-desc">
            Find a company, compare the research collections, and open the report that answers your next question.
        </p>

        <!-- Search Bar with Quick Tags -->
        <form class="sr-search-form" action="{{ route('research') }}" method="GET">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('sector')) <input type="hidden" name="sector" value="{{ request('sector') }}"> @endif
            @if(request('cap')) <input type="hidden" name="cap" value="{{ request('cap') }}"> @endif

            <div class="sr-search-input-wrap">
                <svg class="sr-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="q" name="q" type="text" value="{{ request('q') }}" placeholder="Search by company name, ticker (e.g., BHP, CBA, CSL) or keywords..." autocomplete="off">
                @if(request('q'))
                    <a href="{{ route('research', request()->except('q')) }}" class="sr-search-clear" title="Clear search">✕</a>
                @endif
                <button type="submit" class="btn-green sr-search-btn">Search Research</button>
            </div>

        </form>
    </div>
</section>

<!-- Category Filter Pills Scroller -->
<div class="sr-cat-strip">
    <div class="container">
        <div class="sr-cat-scroller">
            <a href="{{ route('research', request()->except(['category', 'page'])) }}"
               @class(['sr-cat-pill', 'active' => !request('category')])>
                All Research
            </a>
            @foreach($categories as $category)
                <a href="{{ route('research', array_merge(request()->except('page'), ['category' => $category])) }}"
                   @class(['sr-cat-pill', 'active' => request('category') === $category])>
                    {{ $category }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Main Research Layout -->
<section class="container sr-library-section">
    <div>
        <!-- Main Results Grid -->
        <main class="sr-results-main">
            <!-- Results Header & Active Tags -->
            <div class="sr-results-header">
                <div class="sr-results-count">
                    <strong>{{ $reports->total() }}</strong>
                    <span>{{ Str::plural('research report', $reports->total()) }} found</span>
                </div>

                <div class="sr-results-sort">
                    <span class="sr-sort-dot"></span> Latest Published First
                </div>
            </div>

            <!-- Active Filters Pills -->
            @if(request()->hasAny(['q', 'category', 'sector', 'cap']))
                <div class="sr-active-filters-bar">
                    <span class="sr-active-label">Active filters:</span>
                    @if(request('q'))
                        <a href="{{ route('research', request()->except(['q', 'page'])) }}" class="sr-active-chip">
                            Keyword: <strong>"{{ request('q') }}"</strong> ✕
                        </a>
                    @endif
                    @if(request('category'))
                        <a href="{{ route('research', request()->except(['category', 'page'])) }}" class="sr-active-chip">
                            Category: <strong>{{ request('category') }}</strong> ✕
                        </a>
                    @endif
                    @if(request('sector'))
                        <a href="{{ route('research', request()->except(['sector', 'page'])) }}" class="sr-active-chip">
                            Sector: <strong>{{ request('sector') }}</strong> ✕
                        </a>
                    @endif
                    @if(request('cap'))
                        <a href="{{ route('research', request()->except(['cap', 'page'])) }}" class="sr-active-chip">
                            Cap: <strong>{{ request('cap') }}</strong> ✕
                        </a>
                    @endif
                    <a href="{{ route('research') }}" class="sr-clear-all-chip">Clear all</a>
                </div>
            @endif

            <!-- Reports Grid -->
            <div class="reports-grid">
                @forelse($reports as $report)
                    <x-report-card :report="$report" />
                @empty
                    <div class="sr-empty-results">
                        <div class="sr-empty-icon">🔍</div>
                        <h3>No reports match your criteria</h3>
                        <p>Try searching for a different company or clear your filters to view all available coverage.</p>
                        <a href="{{ route('research') }}" class="btn-green">View All Research →</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="sr-pagination-wrap">
                {{ $reports->links() }}
            </div>
        </main>
    </div>
</section>
@endsection
