@extends('layouts.app')
@section('title',$pageContent->title)
@section('content')
@include('components.page-heading')
<section class="section compact container">@include('components.page-body')</section>
@endsection