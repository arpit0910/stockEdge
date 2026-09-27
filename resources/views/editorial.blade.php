@extends('layouts.app')

@section('title', 'Market Insights & Perspectives')

@section('content')
<section class="sr-page-hero">
    <div class="container">
        <span class="sr-hero-pill-badge">
            <span class="dot"></span> MACRO PERSPECTIVES & EDUCATION
        </span>
        <h1 class="sr-page-hero-title">{{ request('topic', 'Market insights') }}</h1>
        <p class="sr-page-hero-desc">
            Clear perspectives on markets, industries, and the practical work of investing.
        </p>

        <form class="sr-search-form editorial-search-form" action="{{ route('editorial') }}" method="GET">
            @if(request('topic')) <input type="hidden" name="topic" value="{{ request('topic') }}"> @endif
            <div class="sr-search-input-wrap">
                <svg class="sr-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="q" name="q" type="text" value="{{ request('q') }}" placeholder="Search articles and insights...">
                @if(request('q'))
                    <a href="{{ route('editorial', request()->except('q')) }}" class="sr-search-clear">✕</a>
                @endif
                <button type="submit" class="btn-green sr-search-btn">Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Topic Filter Strip -->
<div class="sr-cat-strip">
    <div class="container">
        <div class="sr-cat-scroller">
            <a href="{{ route('editorial', request()->except(['topic', 'page'])) }}"
               @class(['sr-cat-pill', 'active' => !request('topic')])>
                All Insights
            </a>
            @foreach(config('stockedge.topics', []) as $topic)
                <a href="{{ route('editorial', array_merge(request()->except('page'), ['topic' => $topic])) }}"
                   @class(['sr-cat-pill', 'active' => request('topic') === $topic])>
                    {{ $topic }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<section class="container sr-library-section">
    <div class="internal-section-heading">
        <div>
            <span class="eyebrow">INSIGHT LIBRARY</span>
            <h2>{{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}</h2>
        </div>
        @if(request()->hasAny(['q', 'topic']))
            <a class="text-link" href="{{ route('editorial') }}">Clear filters</a>
        @else
            <p>Browse the latest perspectives, published newest first.</p>
        @endif
    </div>
    <div class="articles-grid">
        @forelse($articles as $article)
            <x-article-card :article="$article"/>
        @empty
            <div class="sr-empty-results">
                <h3>No articles match your search criteria</h3>
                <p>Try searching for a different topic or view all insights.</p>
                <a href="{{ route('editorial') }}" class="btn-green">View All Insights →</a>
            </div>
        @endforelse
    </div>

    <div class="sr-pagination-wrap">
        {{ $articles->links() }}
    </div>
</section>
@endsection
