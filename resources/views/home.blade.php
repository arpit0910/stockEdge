@extends('layouts.app')

@section('title', 'Australian Stock Market Research & Investment Insights')

@section('content')
    @forelse($sections as $section)
        <x-home-section :section="$section" :reports="$reports" :stocks="$stocks" :collections="$collections" :articles="$articles" :plans="$plans" :testimonials="$testimonials" />
    @empty
        <section class="container section centered">
            <h1>SharesRise</h1>
            <p>Publish a homepage section from the administration workspace to begin.</p>
        </section>
    @endforelse
@endsection
