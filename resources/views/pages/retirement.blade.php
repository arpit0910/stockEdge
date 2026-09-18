@extends('layouts.app')
@section('title', $pageContent->title)
@section('content')
@include('components.page-heading')
<section class="container section compact">
    @include('components.page-body')
    <div class="calculator">
        <form id="retirement-form">
            <h2>Build your scenario</h2>
            <label>Current age<input id="current-age" type="number" min="18" max="99" value="35" required></label>
            <label>Retirement age<input id="retirement-age" type="number" min="19" max="100" value="65" required></label>
            <label>Current savings (AUD)<input id="savings" type="number" min="0" max="100000000" value="50000" required></label>
            <label>Monthly contribution (AUD)<input id="contribution" type="number" min="0" max="1000000" value="500" required></label>
            <label>Annual return assumption (%)<input id="return-rate" type="number" min="-20" max="20" step="0.1" value="5" required></label>
            <button class="button full">Update projection ↗</button>
        </form>
        <div class="projection">
            <span class="eyebrow">YOUR ILLUSTRATIVE PROJECTION</span>
            <h2>Make time part of the plan.</h2>
            <span class="projected-value" id="projection-value" aria-live="polite"></span>
            <p id="projection-summary"></p>
            <div class="projection-bar"><span id="contribution-bar"></span></div>
            <div class="projection-legend"><span>● Starting savings + contributions</span><span>● Illustrative growth</span></div>
            <p class="small-text">Assumes constant annual returns converted to an effective monthly rate, with contributions at month-end. Figures are nominal AUD and exclude inflation, fees, taxes and superannuation rules. Returns are uncertain; this is a maths illustration, not a retirement forecast.</p>
            <a class="text-link" href="{{ route('editorial', ['topic' => 'Retirement']) }}">Explore retirement articles ↗</a>
        </div>
    </div>
</section>
@endsection
