<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:120', 'email' => 'required|email|max:200|unique:users', 'password' => ['required', 'confirmed', PasswordRule::min(8)], 'consent' => 'accepted']);
        $user = User::create(collect($data)->only(['name', 'email', 'password'])->all());
        $user->forceFill(['trial_ends_at' => now()->addDays(7)])->save();
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome to StockEdge. Your seven-day research trial has started.');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($data + ['is_active' => true], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }
        $request->session()->regenerate();

        return redirect()->intended($request->user()->is_admin ? route('admin') : route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function forgot(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);
        Password::sendResetLink($request->only('email'));

        return back()->with('success', 'If an account exists for this email, a reset link has been sent. Local development uses the configured log mailer.');
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate(['token' => 'required', 'email' => 'required|email', 'password' => ['required', 'confirmed', PasswordRule::min(8)]]);
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PasswordReset ? redirect()->route('login')->with('success', 'Password reset. You can now sign in.') : back()->withErrors(['email' => __($status)]);
    }

    public function dashboard(Request $request, string $tab = 'dashboard'): View
    {
        abort_unless(in_array($tab, ['dashboard', 'portfolio', 'watchlist', 'subscription']), 404);
        $holdings = DB::table('holdings')->join('stocks', 'stocks.id', '=', 'holdings.stock_id')->where('user_id', $request->user()->id)->select('holdings.*', 'stocks.name', 'stocks.symbol', 'stocks.price', 'stocks.change')->get();
        $watchlist = DB::table('watchlists')->join('stocks', 'stocks.id', '=', 'watchlists.stock_id')->where('user_id', $request->user()->id)->select('watchlists.*', 'stocks.name', 'stocks.symbol', 'stocks.price', 'stocks.change')->get();

        return view('account', ['tab' => $tab, 'holdings' => $holdings, 'watchlist' => $watchlist, 'stocks' => Stock::all(), 'reports' => Report::with('stock')->where('published', true)->take(3)->get(), 'subscriptions' => DB::table('subscription_requests')->where('user_id', $request->user()->id)->latest()->get()]);
    }

    public function holding(Request $request): RedirectResponse
    {
        $data = $request->validate(['stock_id' => 'required|exists:stocks,id', 'quantity' => 'required|numeric|min:0.0001|max:100000000', 'buy_price' => 'required|numeric|min:0.0001|max:10000000']);
        DB::table('holdings')->insert($data + ['user_id' => $request->user()->id, 'created_at' => now(), 'updated_at' => now()]);

        return back()->with('success', 'Holding added to your portfolio.');
    }

    public function watch(Request $request): RedirectResponse
    {
        $data = $request->validate(['stock_id' => 'required|exists:stocks,id', 'alert_price' => 'nullable|numeric|min:0.0001|max:10000000']);
        DB::table('watchlists')->updateOrInsert(['user_id' => $request->user()->id, 'stock_id' => $data['stock_id']], ['alert_price' => $data['alert_price'] ?? null, 'updated_at' => now(), 'created_at' => now()]);

        return back()->with('success', 'Watchlist saved. Price thresholds are checked against the demo snapshot in your watchlist.');
    }

    public function remove(Request $request, string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['holdings', 'watchlists']), 404);
        $deleted = DB::table($type)->where('id', $id)->where('user_id', $request->user()->id)->delete();
        abort_unless($deleted, 404);

        return back()->with('success', 'Item removed.');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['plan' => ['required', Rule::exists('plans', 'name')->where('published', true)->where('is_trial', false)], 'billing' => ['required', Rule::in(['monthly', 'yearly'])]]);
        DB::table('subscription_requests')->updateOrInsert(['user_id' => $request->user()->id, 'status' => 'pending'], $data + ['created_at' => now(), 'updated_at' => now()]);

        return redirect()->route('account', ['tab' => 'subscription'])->with('success', 'Plan request saved. No payment has been taken; paid activation is not yet connected.');
    }
}
