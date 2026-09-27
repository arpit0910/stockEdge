@extends('layouts.app')

@section('title', ucfirst($tab))

@section('content')
<section class="container account-layout">
    <aside class="account-nav" aria-label="Account navigation">
        <span class="eyebrow">YOUR WORKSPACE</span>
        <h3>{{ auth()->user()->name }}</h3>
        @foreach(['dashboard' => 'Overview', 'portfolio' => 'Portfolio', 'watchlist' => 'Watchlist', 'subscription' => 'Membership'] as $key => $label)
            <a @class(['selected' => $tab === $key]) href="{{ $key === 'dashboard' ? route('dashboard') : route('account', $key) }}">
                {{ $label }} <span>&rarr;</span>
            </a>
        @endforeach
        <a href="{{ route('research') }}">Research library <span>&nearr;</span></a>
        @can('admin')
            <a href="{{ route('admin') }}">Admin studio <span>&nearr;</span></a>
        @endcan
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="link-button">Log out</button>
        </form>
    </aside>

    <div class="account-main">
        <header class="workspace-header">
            <span class="eyebrow">PRIVATE WORKSPACE</span>
            <h1>{{ ['dashboard' => 'Overview', 'portfolio' => 'Portfolio', 'watchlist' => 'Watchlist', 'subscription' => 'Membership'][$tab] }}</h1>
            <p>Manage your saved companies, illustrative holdings, and membership activity in one place.</p>
        </header>

        @php($value = $holdings->sum(fn ($holding) => $holding->quantity * $holding->price))
        @php($cost = $holdings->sum(fn ($holding) => $holding->quantity * $holding->buy_price))

        @if(in_array($tab, ['dashboard', 'portfolio']))
            <div class="metric-grid three workspace-metrics">
                <div class="metric">
                    <small>Portfolio value</small>
                    <strong>${{ number_format($value, 2) }}</strong>
                </div>
                <div class="metric">
                    <small>Unrealised gain or loss</small>
                    <strong class="{{ $value - $cost >= 0 ? 'positive' : 'negative' }}">${{ number_format($value - $cost, 2) }}</strong>
                </div>
                <div class="metric">
                    <small>{{ $tab === 'dashboard' ? 'Companies on watchlist' : 'Invested capital' }}</small>
                    <strong>{{ $tab === 'dashboard' ? $watchlist->count() : '$'.number_format($cost, 2) }}</strong>
                </div>
            </div>
        @endif

        @if($tab === 'dashboard')
            <div class="welcome-panel workspace-callout">
                <div>
                    <span class="eyebrow">RESEARCH ACCESS</span>
                    <h2>{{ auth()->user()->trial_ends_at?->isFuture() ? 'Your trial is active.' : 'Continue with open research.' }}</h2>
                    <p>{{ auth()->user()->trial_ends_at?->isFuture() ? 'Premium access ends '.auth()->user()->trial_ends_at->format('d M Y').'.' : 'Your trial has ended. Your portfolio and watchlist remain available.' }}</p>
                </div>
                <a class="button" href="{{ route('research') }}">Open research library</a>
            </div>

            <div class="workspace-section-heading">
                <div><span class="eyebrow">LATEST COVERAGE</span><h2>From the research desk</h2></div>
                <a class="text-link" href="{{ route('research') }}">View all &rarr;</a>
            </div>
            <div class="reports-grid">
                @foreach($reports as $report)
                    <x-report-card :report="$report"/>
                @endforeach
            </div>
        @endif

        @if($tab === 'portfolio')
            <section class="panel workspace-task-panel">
                <div class="workspace-section-heading compact">
                    <div><span class="eyebrow">NEW POSITION</span><h2>Add a holding</h2></div>
                    <p>Use your average purchase price and current quantity.</p>
                </div>
                <form class="inline-form" method="post" action="{{ route('holding') }}">
                    @csrf
                    <label>Company<select name="stock_id">@foreach($stocks as $company)<option value="{{ $company->id }}">{{ $company->symbol }} &middot; {{ $company->name }}</option>@endforeach</select></label>
                    <label>Quantity<input type="number" name="quantity" step="0.0001" min="0.0001" required></label>
                    <label>Average buy price (AUD)<input type="number" name="buy_price" step="0.0001" min="0.0001" required></label>
                    <button class="button">Add holding</button>
                </form>
            </section>

            <div class="workspace-section-heading">
                <div><span class="eyebrow">YOUR HOLDINGS</span><h2>Portfolio positions</h2></div>
                <p>{{ $holdings->count() }} {{ Str::plural('position', $holdings->count()) }}</p>
            </div>
            <div class="table-scroll panel data-table-panel" tabindex="0" aria-label="Portfolio positions table">
                <table>
                    <thead><tr><th>Company</th><th>Quantity</th><th>Buy price</th><th>Value</th><th>Gain / loss</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($holdings as $holding)
                            <tr>
                                <td><a href="{{ route('stock', $holding->symbol) }}"><b>{{ $holding->symbol }}</b><small>{{ $holding->name }}</small></a></td>
                                <td>{{ (float) $holding->quantity }}</td>
                                <td>${{ number_format($holding->buy_price, 2) }}</td>
                                <td>${{ number_format($holding->price * $holding->quantity, 2) }}</td>
                                <td class="{{ $holding->price >= $holding->buy_price ? 'positive' : 'negative' }}">${{ number_format(($holding->price - $holding->buy_price) * $holding->quantity, 2) }}</td>
                                <td><form method="post" action="{{ route('remove', ['type' => 'holdings', 'id' => $holding->id]) }}">@csrf @method('DELETE')<button class="link-button danger">Remove</button></form></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">Add your first holding using the form above.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="small-text muted">Values exclude dividends, fees, and tax. Multiple purchases are recorded as separate lots.</p>
        @endif

        @if($tab === 'watchlist')
            <section class="panel workspace-task-panel">
                <div class="workspace-section-heading compact">
                    <div><span class="eyebrow">FOLLOW A COMPANY</span><h2>Add to watchlist</h2></div>
                    <p>Optionally set an illustrative price threshold.</p>
                </div>
                <form class="inline-form watchlist-form" method="post" action="{{ route('watch') }}">
                    @csrf
                    <label>Company<select name="stock_id">@foreach($stocks as $company)<option value="{{ $company->id }}">{{ $company->symbol }} &middot; {{ $company->name }}</option>@endforeach</select></label>
                    <label>Alert price (optional)<input type="number" name="alert_price" min="0.0001" step="0.0001" placeholder="AUD"></label>
                    <button class="button">Save company</button>
                </form>
            </section>

            <div class="workspace-section-heading">
                <div><span class="eyebrow">SAVED COMPANIES</span><h2>Your watchlist</h2></div>
                <p>{{ $watchlist->count() }} {{ Str::plural('company', $watchlist->count()) }}</p>
            </div>
            <div class="table-scroll panel data-table-panel" tabindex="0" aria-label="Watchlist table">
                <table>
                    <thead><tr><th>Company</th><th>Demo price</th><th>Change</th><th>Threshold</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($watchlist as $company)
                            <tr>
                                <td><a href="{{ route('stock', $company->symbol) }}"><b>{{ $company->symbol }}</b><small>{{ $company->name }}</small></a></td>
                                <td>${{ number_format($company->price, 2) }}</td>
                                <td class="{{ $company->change >= 0 ? 'positive' : 'negative' }}">{{ $company->change >= 0 ? '+' : '' }}{{ $company->change }}%</td>
                                <td>{{ $company->alert_price ? ($company->price <= $company->alert_price ? 'Threshold reached' : 'Watching').' at $'.number_format($company->alert_price, 2) : 'Not set' }}</td>
                                <td><form method="post" action="{{ route('remove', ['type' => 'watchlists', 'id' => $company->id]) }}">@csrf @method('DELETE')<button class="link-button danger">Remove</button></form></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state">Add a company using the form above.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($tab === 'subscription')
            <section class="panel membership-summary">
                <span class="eyebrow">CURRENT ACCESS</span>
                <h2>{{ auth()->user()->trial_ends_at?->isFuture() ? 'Explorer trial' : 'Explorer' }}</h2>
                <p>{{ auth()->user()->trial_ends_at?->isFuture() ? 'Premium research access until '.auth()->user()->trial_ends_at->format('d M Y').'.' : 'Open research and investor tools remain available.' }}</p>
                <a class="button" href="{{ route('page', 'pricing') }}">Compare membership plans</a>
            </section>

            <div class="workspace-section-heading">
                <div><span class="eyebrow">REQUEST HISTORY</span><h2>Plan requests</h2></div>
            </div>
            <div class="membership-request-list">
                @forelse($subscriptions as $subscription)
                    <article class="panel membership-request">
                        <div><b>{{ $subscription->plan }}</b><span>{{ ucfirst($subscription->billing) }} billing</span></div>
                        <span class="tag">{{ ucfirst($subscription->status) }}</span>
                        <p>No charge has been made. Paid checkout and activation are not connected in this demo.</p>
                    </article>
                @empty
                    <div class="empty-state workspace-empty">You have no plan requests.</div>
                @endforelse
            </div>
        @endif
    </div>
</section>
@endsection
