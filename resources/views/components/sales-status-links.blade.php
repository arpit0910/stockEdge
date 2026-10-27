@props(['routeName'])

<div class="crm-status-links" aria-label="{{ $routeName === 'sales.contacts' ? 'Contact' : 'Lead' }} statuses">
    @foreach(\App\Support\SalesCrm::pipelineOptions() as $value => $label)
        <a @class(['a-nav-link', 'active' => request()->routeIs($routeName) && request('status') === $value]) href="{{ route($routeName, ['status' => $value]) }}" @if(request()->routeIs($routeName) && request('status') === $value) aria-current="page" @endif><x-admin-icon :name="$value"/>{{ $label }}</a>
    @endforeach
</div>
