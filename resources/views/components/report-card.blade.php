@props(['report'])
<article class="report-card">
    <div class="card-top">
        <span class="tag">{{ $report->category }}</span>
        <span class="access-label {{ $report->premium ? 'member' : 'open' }}">
            {{ $report->premium ? '● MEMBER' : '○ OPEN' }}
        </span>
    </div>

    <div class="company-row">
        <span class="company-icon {{ strtolower($report->stock->symbol) }}">
            {{ substr($report->stock->symbol, 0, 1) }}
        </span>
        <div class="company-info">
            <b>{{ $report->stock->symbol }} <small class="stock-name-label">· {{ $report->stock->name }}</small></b>
            <small>ASX · {{ $report->stock->sector }} · {{ $report->stock->cap }}</small>
        </div>
        <span class="rating {{ strtolower($report->rating) }}">
            {{ $report->rating }}
        </span>
    </div>

    <h3><a href="{{ route('report', $report->slug) }}">{{ $report->title }}</a></h3>
    <p>{{ $report->summary }}</p>

    <div class="card-bottom">
        <span>{{ $report->created_at->format('d M Y') }} · 5 min read</span>
        <a href="{{ route('report', $report->slug) }}" class="card-read-link" aria-label="Read {{ $report->title }}">
            Read Report →
        </a>
    </div>
</article>