<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LoadSiteContent
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Schema::hasTable('taxonomies')) {
                foreach (['category' => 'categories', 'sector' => 'sectors', 'topic' => 'topics'] as $kind => $key) {
                    $items = DB::table('taxonomies')->where('kind', $kind)->orderBy('position')->orderBy('id')->pluck('name')->all();
                    if (! empty($items)) {
                        config(['stockedge.'.$key => $items]);
                    }
                }
            }
            if (Schema::hasTable('site_settings')) {
                $settings = DB::table('site_settings')->pluck('value', 'key')->all();
                if (! empty($settings)) {
                    config(['stockedge.site' => $settings]);
                }
            }
            if (Schema::hasTable('site_pages')) {
                config(['stockedge.footer_pages' => DB::table('site_pages')->where('published', true)->where('show_in_footer', true)->orderBy('position')->get()]);
            }
            if (Schema::hasTable('plans')) {
                config(['stockedge.plan_records' => DB::table('plans')->where('published', true)->orderBy('position')->get()]);
            }
            if (Schema::hasTable('stocks')) {
                config(['stockedge.market_snapshot' => DB::table('stocks')->orderBy('symbol')->limit(4)->get()]);
            }
        } catch (\Throwable) {
            // Silently fall back to default configurations
        }

        if ($request->user() && ! $request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'This account is suspended. Please contact the site administrator.']);
        }

        return $next($request);
    }
}
