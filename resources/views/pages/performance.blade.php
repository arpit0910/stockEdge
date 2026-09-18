@extends('layouts.app')
@section('title', $pageContent->title)
@section('content')
@include('components.page-heading')
<section class="container section compact">
    <div class="notice">Illustrative calculations only. {{ config('stockedge.site.brand_name', 'SharesRise') }} has no verified recommendation track record. These fictional examples include gains and losses.</div>
    <div class="panel table-scroll">
        <table>
            <thead><tr><th>Illustrative position</th><th>Buy price</th><th>Sell price</th><th>Dividends</th><th>Total return</th></tr></thead>
            <tbody>@foreach([['Example A', 10, 12, 0.2], ['Example B', 25, 21, 0.5], ['Example C', 8, 8.4, 0], ['Example D', 40, 36, 1.2]] as [$name, $buy, $sell, $dividend])<tr><td>{{ $name }}</td><td>${{ number_format($buy, 2) }}</td><td>${{ number_format($sell, 2) }}</td><td>${{ number_format($dividend, 2) }}</td><td class="{{ $sell + $dividend >= $buy ? 'positive' : 'negative' }}">{{ number_format(($sell - $buy + $dividend) / $buy * 100, 2) }}%</td></tr>@endforeach</tbody>
        </table>
    </div>
    @include('components.page-body')
</section>
@endsection
