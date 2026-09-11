@extends('layouts.app')
@section('title','Company research & investor tools')
@section('content')
@foreach($sections as $section)
@include('components.home-section',['section'=>$section])
@endforeach
@endsection