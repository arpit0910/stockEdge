@extends('layouts.app')
@section('title', $pageContent->title)
@section('content')
@include('components.page-heading')
<section class="container section compact">
    @include('components.page-body')
</section>
@endsection
