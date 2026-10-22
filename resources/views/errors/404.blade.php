@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
<main class="sr-not-found container">
    <span class="eyebrow">404 · PAGE NOT FOUND</span>
    <h1>This page could not be found.</h1>
    <p>You’ll be redirected to SharesRise in a few seconds.</p>
    <a class="btn-green" href="{{ route('home') }}">Go to homepage</a>
</main>
<script>
    window.setTimeout(() => window.location.replace(@json(route('home'))), 5000);
</script>
@endsection
