@extends('layouts.app')
@section('title',$pageContent->title)
@section('content')
@include('components.page-heading')
<section class="section compact container"><div class="collection-grid">@foreach(config('stockedge.sectors') as $sector)<a class="collection-card" href="{{ route('research',['sector'=>$sector]) }}"><span class="collection-number">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $sector }}</h3><p>{{ $stocks->where('sector',$sector)->count() }} companies in demo coverage</p><span class="round-arrow">↗</span></a>@endforeach</div>@include('components.page-body')</section>
@endsection