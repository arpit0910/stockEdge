@extends('layouts.app')

@section('title', $item->title)

@section('content')
<section class="container detail-layout">
    <article>
        <a class="back-link" href="{{ $kind === 'report' ? route('research') : route('editorial') }}">
            ← Back to {{ $kind === 'report' ? 'Research Library' : 'Market Insights' }}
        </a>

        <div class="article-meta">
            <span class="tag">{{ $kind === 'report' ? $item->category : $item->topic }}</span>
            <span>Published {{ $item->created_at->format('d F Y') }}</span>
            <span>• 5 min read</span>
        </div>

        <h1 style="margin-top: 14px; margin-bottom: 18px; font-size: 38px; line-height: 1.2;">{{ $item->title }}</h1>
        <p class="lead-text">{{ $item->summary }}</p>

        <div class="author">
            <span class="company-icon">SR</span>
            <div>
                <b>SharesRise Research Desk</b>
                <small>Independent thinking. Informed investing.</small>
            </div>
        </div>

        @if($canRead)
            <div class="prose">
                @foreach(explode("\n\n", $item->body) as $paragraph)
                    <section>
                        @php($lines = explode("\n", $paragraph, 2))
                        <h2>{{ $lines[0] }}</h2>
                        @if(isset($lines[1]))
                            <p>{{ $lines[1] }}</p>
                        @endif
                    </section>
                @endforeach
            </div>

            <div style="margin-top: 40px; display: flex; gap: 14px;">
                <button class="button outline no-print" onclick="window.print()">
                    🖨️ Print / Save as PDF
                </button>
                <a href="{{ route('research') }}" class="button light no-print">
                    Browse More Research →
                </a>
            </div>
        @else
            <div class="locked-content">
                <span style="font-size: 44px; color: var(--green-500); display: block; margin-bottom: 12px;">🔒</span>
                <h2>A deeper perspective awaits.</h2>
                <p>This report is part of our member research library. Start your seven-day trial to read the full analysis.</p>
                <a class="btn-green" href="{{ route('register') }}" style="margin-top: 12px;">Start Your 7-Day Free Trial →</a>
                <a href="{{ route('login') }}" style="display: block; margin-top: 16px; font-size: 13px; color: var(--green-600); font-weight: 600;">Already a member? Log in</a>
            </div>
        @endif
    </article>

    <!-- Detail Aside Snapshot -->
    <aside class="detail-aside">
        @if($kind === 'report')
            <span class="eyebrow">COMPANY SNAPSHOT</span>
            <h2>{{ $item->stock->symbol }}</h2>
            <p style="font-size: 14px; color: var(--slate-600); margin-bottom: 12px;">{{ $item->stock->name }}</p>
            <div class="price">${{ number_format($item->stock->price, 2) }}</div>
            <span class="rating {{ strtolower($item->rating) }}">{{ $item->rating }} · Valuation Rating</span>
            <hr style="border: 0; border-top: 1px solid var(--slate-200); margin: 20px 0;">
            <p style="font-size: 13px; color: var(--slate-600); margin-bottom: 16px;">
                <strong>Sector:</strong> {{ $item->stock->sector }}<br>
                <strong>Capitalisation:</strong> {{ $item->stock->cap }}
            </p>
            <a class="btn-green" href="{{ route('stock', $item->stock->symbol) }}" style="width: 100%;">Company Overview ↗</a>
        @else
            <span class="eyebrow">KEEP EXPLORING</span>
            <h2>Follow your curiosity.</h2>
            <p style="font-size: 13px; color: var(--slate-600); margin-bottom: 20px;">Discover the research behind a more considered approach.</p>
            <a class="btn-green" href="{{ route('research') }}" style="width: 100%;">Explore Research Library ↗</a>
        @endif
        <p class="muted small-text" style="margin-top: 20px; font-size: 11px; line-height: 1.5;">Illustrative content only. No live prices or personalized financial advice.</p>
    </aside>
</section>
@endsection