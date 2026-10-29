<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Report;
use App\Models\Stock;
use App\Models\Taxonomy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $homepageStocks = Stock::query()
            ->where('show_on_homepage', true)
            ->orderBy('homepage_position')
            ->orderBy('id')
            ->get();
        $topAsxPicks = Stock::query()
            ->where('top_asx_pick', true)
            ->orderBy('homepage_position')
            ->orderBy('id')
            ->get();

        return view('home', [
            'sections' => DB::table('site_sections')->where('published', true)->orderBy('position')->orderBy('id')->get(),
            'siteSections' => DB::table('site_sections')->where('published', true)->get()->keyBy('layout'),
            'collections' => Taxonomy::query()->categories()->published()->ordered()->get(),
            'homepageStocks' => $homepageStocks,
            'latestCoverageStocks' => Stock::query()->where('show_in_latest_coverage', true)->orderBy('homepage_position')->orderBy('id')->get(),
            'topAsxPicks' => $topAsxPicks,
            'workspaceStock' => $topAsxPicks->first() ?? $homepageStocks->first(),
            'reports' => Report::with('stock')->where('published', true)->latest()->take(12)->get(),
            'articles' => Article::where('published', true)->latest()->orderByDesc('id')->take(3)->get(),
            'plans' => DB::table('plans')->where('published', true)->where('show_on_homepage', true)->orderBy('position')->orderBy('id')->limit(3)->get(),
            'testimonials' => DB::table('testimonials')->where('published', true)->orderBy('position')->get(),
            'faqs' => DB::table('faqs')->where('published', true)->orderBy('position')->get(),
        ]);
    }

    public function research(Request $request): View
    {
        $categories = Taxonomy::query()->categories()->ordered()->pluck('name');
        $data = $request->validate(['q' => 'nullable|string|max:100', 'category' => ['nullable', Rule::in($categories->all())], 'sector' => ['nullable', Rule::in(config('stockedge.sectors'))], 'cap' => ['nullable', Rule::in(['Blue Chip', 'Mid Cap', 'Small Cap'])]]);
        $query = Report::with('stock')->where('published', true);
        if (! empty($data['q'])) {
            $term = $data['q'];
            $query->where(fn ($q) => $q->where('title', 'like', "%$term%")->orWhereHas('stock', fn ($s) => $s->where('name', 'like', "%$term%")->orWhere('symbol', 'like', "%$term%")));
        }
        if (! empty($data['category'])) {
            $query->where('category', $data['category']);
        }
        foreach (['sector', 'cap'] as $field) {
            if (! empty($data[$field])) {
                $query->whereHas('stock', fn ($s) => $s->where($field, $data[$field]));
            }
        }

        return view('research', ['reports' => $query->latest()->paginate(9)->withQueryString(), 'categories' => $categories]);
    }

    public function report(Request $request, Report $report): View
    {
        abort_unless($report->published, 404);
        $canRead = ! $report->premium || ($request->user() && ($request->user()->is_admin || $request->user()->trial_ends_at?->isFuture()));

        return view('detail', ['item' => $report->load('stock'), 'kind' => 'report', 'canRead' => $canRead]);
    }

    public function stock(Stock $stock): View
    {
        return view('stock', ['stock' => $stock, 'reports' => $stock->reports()->where('published', true)->get()]);
    }

    public function editorial(Request $request): View
    {
        $request->validate(['topic' => ['nullable', Rule::in(config('stockedge.topics'))], 'q' => 'nullable|string|max:100']);
        $query = Article::where('published', true);
        if ($request->filled('topic')) {
            $query->where('topic', $request->string('topic')->toString());
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->string('q').'%');
        }

        return view('editorial', ['articles' => $query->latest()->paginate(9)->withQueryString()]);
    }

    public function article(Article $article): View
    {
        abort_unless($article->published, 404);

        return view('detail', ['item' => $article, 'kind' => 'article', 'canRead' => true]);
    }

    public function page(string $page): View
    {
        $pageContent = DB::table('site_pages')->where('slug', $page)->where('published', true)->first();
        abort_unless($pageContent, 404);
        $template = in_array($page, ['about', 'contact', 'free-report', 'performance', 'retirement', 'pricing', 'sectors']) ? 'pages.'.$page : 'pages.cms';

        return view($template, ['stocks' => Stock::all(), 'pageContent' => $pageContent]);
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['location' => route('home'), 'modified' => null, 'frequency' => 'daily', 'priority' => '1.0'],
            ['location' => route('research'), 'modified' => Report::where('published', true)->max('updated_at'), 'frequency' => 'daily', 'priority' => '0.9'],
            ['location' => route('editorial'), 'modified' => Article::where('published', true)->max('updated_at'), 'frequency' => 'daily', 'priority' => '0.9'],
        ]);

        $urls = $urls
            ->concat(DB::table('site_pages')->where('published', true)->get()->map(fn (object $page): array => ['location' => $page->slug === 'about' ? route('about') : route('page', $page->slug), 'modified' => $page->updated_at, 'frequency' => 'monthly', 'priority' => '0.7']))
            ->concat(Article::where('published', true)->get()->map(fn (Article $article): array => ['location' => route('article', $article->slug), 'modified' => $article->updated_at, 'frequency' => 'monthly', 'priority' => '0.8']))
            ->concat(Report::where('published', true)->get()->map(fn (Report $report): array => ['location' => route('report', $report->slug), 'modified' => $report->updated_at, 'frequency' => 'monthly', 'priority' => '0.8']))
            ->concat(Stock::all()->map(fn (Stock $stock): array => ['location' => route('stock', $stock->symbol), 'modified' => $stock->updated_at, 'frequency' => 'weekly', 'priority' => '0.7']));

        return response(view('sitemap', ['urls' => $urls]))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /sales\nDisallow: /account\nDisallow: /dashboard\nDisallow: /login\nDisallow: /register\n\nSitemap: ".route('sitemap')."\n";

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function lead(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:120',
            'email' => 'required|email|max:200',
            'phone' => 'nullable|string|max:30',
            'country_code' => 'nullable|string|max:10',
            'investor_type' => 'nullable|string|max:60',
            'market_focus' => 'nullable|string|max:60',
            'portfolio_size' => 'nullable|string|max:60',
            'type' => ['required', Rule::in(['newsletter', 'report', 'contact'])],
            'message' => 'nullable|string|max:5000',
            'consent' => 'accepted',
        ]);
        if ($data['type'] === 'contact') {
            $request->validate(['name' => 'required', 'message' => 'required']);
        }

        $phone = $data['phone'] ?? null;
        if (! empty($phone) && ! empty($data['country_code'])) {
            $phone = trim($data['country_code'].' '.$phone);
        }

        $metaLines = [];
        if (! empty($data['investor_type'])) {
            $metaLines[] = "Investor Type: {$data['investor_type']}";
        }
        if (! empty($data['market_focus'])) {
            $metaLines[] = "Market Focus: {$data['market_focus']}";
        }
        if (! empty($data['portfolio_size'])) {
            $metaLines[] = "Portfolio Size: {$data['portfolio_size']}";
        }

        $finalMessage = $data['message'] ?? '';
        if (! empty($metaLines)) {
            $metaHeader = '['.implode(' | ', $metaLines).']';
            $finalMessage = $finalMessage !== '' ? $metaHeader."\n\n".$finalMessage : $metaHeader;
        }

        DB::table('leads')->insert([
            'name' => $data['name'] ?? null,
            'email' => $data['email'],
            'phone' => $phone,
            'type' => $data['type'],
            'message' => $finalMessage,
            'consent' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($data['type'] === 'report') {
            return redirect()->route('sample')->with('success', 'Your sample report is ready.');
        }

        return back()->with('success', $data['type'] === 'contact' ? 'Your message has been saved. Thank you for getting in touch.' : 'You are on the list. Your newsletter preference has been saved.');
    }

    public function sample(): Response
    {
        return response(view('sample'))->header('Content-Type', 'text/html')->header('Cache-Control', 'private, no-store');
    }
}
