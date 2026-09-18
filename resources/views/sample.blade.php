@extends('layouts.app')
@section('title', 'Investor research checklist')
@section('content')
<article class="container sample-report prose">
    <span class="eyebrow">{{ strtoupper(config('stockedge.site.brand_name', 'SharesRise')) }} · COMPLIMENTARY RESEARCH GUIDE</span>
    <h1>A better question.<br>A clearer perspective.</h1>
    <p class="lead-text">A practical checklist for getting to know a business.</p>
    @foreach([
        'Understand the business' => 'Explain how the business earns revenue, who its customers are and why they choose it. Identify the industry forces that could change that picture.',
        'Follow the cash' => 'Compare cash flow with reported profit. Look at working capital, ongoing capital expenditure and debt repayment needs.',
        'Think in scenarios' => 'Consider a range of outcomes instead of relying on one forecast. Ask which assumptions matter most and how sensitive the valuation is to them.',
        'Know what could go wrong' => 'List competitive, financial and operational risks. Decide what new evidence would cause you to revisit your thesis.',
        'Keep a research journal' => 'Record the evidence behind your view, the unanswered questions and the date you plan to review the business again.',
    ] as $heading => $body)
        <h2>{{ $loop->iteration }}. {{ $heading }}</h2>
        <p>{{ $body }}</p>
    @endforeach
    <p class="notice">This is an educational demonstration, not financial advice or a stock recommendation.</p>
    <button class="button no-print" onclick="window.print()">Print / save report as PDF ↗</button>
</article>
@endsection
