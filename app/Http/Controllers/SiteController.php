<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Report;
use App\Models\Stock;
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
        $marketIndices = [
            ['name' => 'S&P/ASX 200', 'code' => 'XJO', 'price' => '7,812.60', 'high' => '7,845.20', 'low' => '7,120.40', 'change' => '+1.28%'],
            ['name' => 'All Ordinaries', 'code' => 'XAO', 'price' => '8,064.30', 'high' => '8,095.10', 'low' => '7,340.50', 'change' => '+1.15%'],
            ['name' => 'S&P/ASX Small Ords', 'code' => 'XSO', 'price' => '3,088.10', 'high' => '3,110.40', 'low' => '2,780.20', 'change' => '+0.84%'],
            ['name' => 'S&P/ASX 50', 'code' => 'XFL', 'price' => '7,245.90', 'high' => '7,280.00', 'low' => '6,610.80', 'change' => '+1.42%'],
            ['name' => 'ASX MidCap 50', 'code' => 'XMD', 'price' => '9,415.70', 'high' => '9,480.20', 'low' => '8,520.10', 'change' => '+0.95%'],
        ];

        $pastRecommendations = [
            ['symbol' => 'BHP', 'name' => 'BHP Group Limited', 'sector' => 'Mining', 'buy' => 38.40, 'target' => 46.50, 'return' => '+21.1%', 'status' => 'Target Achieved', 'active' => false],
            ['symbol' => 'CBA', 'name' => 'Commonwealth Bank', 'sector' => 'Banking', 'buy' => 108.20, 'target' => 135.00, 'return' => '+24.8%', 'status' => 'Target Achieved', 'active' => false],
            ['symbol' => 'CSL', 'name' => 'CSL Limited', 'sector' => 'Healthcare', 'buy' => 264.50, 'target' => 310.00, 'return' => '+17.2%', 'status' => 'Active', 'active' => true],
            ['symbol' => 'WDS', 'name' => 'Woodside Energy Group', 'sector' => 'Energy', 'buy' => 24.80, 'target' => 32.00, 'return' => '+29.0%', 'status' => 'Target Achieved', 'active' => false],
            ['symbol' => 'XRO', 'name' => 'Xero Limited', 'sector' => 'Technology', 'buy' => 118.00, 'target' => 155.00, 'return' => '+31.4%', 'status' => 'Active', 'active' => true],
        ];

        return view('home', [
            'sections' => DB::table('site_sections')->where('published', true)->orderBy('position')->orderBy('id')->get(),
            'siteSections' => DB::table('site_sections')->where('published', true)->get()->keyBy('layout'),
            'collections' => DB::table('taxonomies')->where('kind', 'category')->orderBy('position')->get(),
            'stocks' => Stock::take(12)->get(),
            'reports' => Report::with('stock')->where('published', true)->latest()->take(12)->get(),
            'articles' => Article::where('published', true)->latest()->take(3)->get(),
            'plans' => DB::table('plans')->where('published', true)->orderBy('position')->get(),
            'testimonials' => DB::table('testimonials')->where('published', true)->orderBy('position')->get(),
            'faqs' => DB::table('faqs')->where('published', true)->orderBy('position')->get(),
            'marketIndices' => $marketIndices,
            'pastRecommendations' => $pastRecommendations,
        ]);
    }

    public function research(Request $request): View
    {
        $data = $request->validate(['q' => 'nullable|string|max:100', 'category' => ['nullable', Rule::in(config('stockedge.categories'))], 'sector' => ['nullable', Rule::in(config('stockedge.sectors'))], 'cap' => ['nullable', Rule::in(['Blue Chip', 'Mid Cap', 'Small Cap'])]]);
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

        return view('research', ['reports' => $query->latest()->paginate(9)->withQueryString()]);
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
        $template = in_array($page, ['contact', 'free-report', 'performance', 'retirement', 'pricing', 'sectors']) ? 'pages.'.$page : 'pages.cms';

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
            ->concat(DB::table('site_pages')->where('published', true)->get()->map(fn (object $page): array => ['location' => route('page', $page->slug), 'modified' => $page->updated_at, 'frequency' => 'monthly', 'priority' => '0.7']))
            ->concat(Article::where('published', true)->get()->map(fn (Article $article): array => ['location' => route('article', $article->slug), 'modified' => $article->updated_at, 'frequency' => 'monthly', 'priority' => '0.8']))
            ->concat(Report::where('published', true)->get()->map(fn (Report $report): array => ['location' => route('report', $report->slug), 'modified' => $report->updated_at, 'frequency' => 'monthly', 'priority' => '0.8']))
            ->concat(Stock::all()->map(fn (Stock $stock): array => ['location' => route('stock', $stock->symbol), 'modified' => $stock->updated_at, 'frequency' => 'weekly', 'priority' => '0.7']));

        return response(view('sitemap', ['urls' => $urls]))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /account\nDisallow: /dashboard\nDisallow: /login\nDisallow: /register\n\nSitemap: ".route('sitemap')."\n";

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function lead(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'nullable|string|max:120', 'email' => 'required|email|max:200', 'phone' => 'nullable|string|max:30', 'type' => ['required', Rule::in(['newsletter', 'report', 'contact'])], 'message' => 'nullable|string|max:5000', 'consent' => 'accepted']);
        if ($data['type'] === 'contact') {
            $request->validate(['name' => 'required', 'message' => 'required']);
        }
        DB::table('leads')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
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
