@extends('layouts.app')

@section('title', $item->title)

@section('content')
<section class="container detail-layout internal-detail-shell">
    <article class="internal-detail-main">
        <a class="back-link" href="{{ $kind === 'report' ? route('research') : route('editorial') }}">
            &larr; Back to {{ $kind === 'report' ? 'research' : 'insights' }}
        </a>

        <header class="internal-detail-header">
            <div class="article-meta internal-detail-meta">
                <span class="tag">{{ $kind === 'report' ? $item->category : $item->topic }}</span>
                <span>Published {{ $item->created_at->format('d F Y') }}</span>
                <span>5 min read</span>
            </div>

            <h1>{{ $item->title }}</h1>
            <p class="lead-text">{{ $item->summary }}</p>

            <div class="author internal-detail-byline">
                <span class="company-icon" aria-hidden="true">SR</span>
                <div>
                    <b>SharesRise Research Desk</b>
                    <small>Independent thinking. Informed investing.</small>
                </div>
            </div>
        </header>

        @if($canRead)
            <div class="prose internal-article-body">
                @if(str_contains($item->body, '##') || str_contains($item->body, '| ') || str_contains($item->body, "- ") || str_contains($item->body, '<') || str_contains($item->body, '**'))
                    {!! \Illuminate\Support\Str::markdown($item->body) !!}
                @else
                    @foreach(explode("\n\n", $item->body) as $paragraph)
                        <section>
                            @php($lines = explode("\n", $paragraph, 2))
                            <h2>{{ $lines[0] }}</h2>
                            @if(isset($lines[1]))
                                <p>{{ $lines[1] }}</p>
                            @endif
                        </section>
                    @endforeach
                @endif
            </div>

            <div class="inline-actions internal-detail-actions no-print">
                <button class="button outline" onclick="window.print()">Print or save as PDF</button>
                <a href="{{ $kind === 'report' ? route('research') : route('editorial') }}" class="button light">
                    Browse more {{ $kind === 'report' ? 'research' : 'insights' }} &rarr;
                </a>
            </div>
        @else
            <div class="locked-content">
                <span class="locked-content-icon" aria-hidden="true">&#128274;</span>
                <h2>A deeper perspective awaits.</h2>
                <p>This report is part of our member research library. Start your seven-day trial to read the full analysis.</p>
                <a class="btn-green" href="{{ route('register') }}">Start your free trial &rarr;</a>
                <a class="locked-content-login" href="{{ route('login') }}">Already a member? Log in</a>
            </div>
        @endif
    </article>

    <aside class="detail-aside" aria-label="Supporting details">
        @if($kind === 'report')
            <span class="eyebrow">COMPANY SNAPSHOT</span>
            <h2>{{ $item->stock->symbol }}</h2>
            <p class="detail-company-name">{{ $item->stock->name }}</p>
            <div class="price">${{ number_format($item->stock->price, 2) }}</div>
            <span class="rating {{ strtolower($item->rating) }}">{{ $item->rating }} rating</span>

            <dl class="detail-summary-list">
                <div><dt>Sector</dt><dd>{{ $item->stock->sector }}</dd></div>
                <div><dt>Capitalisation</dt><dd>{{ $item->stock->cap }}</dd></div>
            </dl>

            <a class="btn-green detail-aside-action" href="{{ route('stock', $item->stock->symbol) }}">View company overview &rarr;</a>
        @else
            <span class="eyebrow">KEEP EXPLORING</span>
            <h2>Continue your research.</h2>
            <p>Explore company reports and practical investment research in the library.</p>
            <a class="btn-green detail-aside-action" href="{{ route('research') }}">Explore research &rarr;</a>
        @endif

        <p class="detail-disclaimer">Illustrative content only. No live prices or personalised financial advice.</p>
    </aside>
</section>
@endsection
