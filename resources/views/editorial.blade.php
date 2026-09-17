@extends('layouts.app')

@section('title', 'Market Insights & Perspectives')

@section('content')
<section class="sr-page-hero">
    <div class="container">
        <span class="sr-hero-pill-badge">
            <span class="dot"></span> MACRO PERSPECTIVES & EDUCATION
        </span>
        <h1 class="sr-page-hero-title">{{ request('topic', 'Market Insights & Industry Trends') }}</h1>
        <p class="sr-page-hero-desc">
            Macro analysis, industry trends, investing frameworks, and expert market commentary from our team.
        </p>

        <form class="sr-search-form" action="{{ route('editorial') }}" method="GET" style="max-width: 540px;">
            @if(request('topic')) <input type="hidden" name="topic" value="{{ request('topic') }}"> @endif
            <div class="sr-search-input-wrap">
                <svg class="sr-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
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
    <div class="articles-grid">
        @forelse($articles as $article)
            <x-article-card :article="$article"/>
        @empty
            <div class="sr-empty-results" style="grid-column: 1 / -1;">
                <div class="sr-empty-icon">📰</div>
                <h3>No articles match your search criteria</h3>
                <p>Try searching for a different topic or view all insights.</p>
                <a href="{{ route('editorial') }}" class="btn-green">View All Insights →</a>
            </div>
        @endforelse
    </div>

    <div class="sr-pagination-wrap" style="margin-top: 40px;">
        {{ $articles->links() }}
    </div>
</section>
@endsection