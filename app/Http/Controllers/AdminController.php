<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Report;
use App\Models\Stock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin', ['reports' => Report::with('stock')->latest()->get(), 'articles' => Article::latest()->get(), 'stocks' => Stock::all(), 'leads' => DB::table('leads')->latest()->get(), 'subscriptions' => DB::table('subscription_requests')->join('users', 'users.id', '=', 'subscription_requests.user_id')->select('subscription_requests.*', 'users.email')->get()]);
    }

    public function save(Request $request, string $type): RedirectResponse
    {
        abort_unless(in_array($type, ['report', 'article']), 404);
        $table = $type === 'report' ? 'reports' : 'articles';
        $id = $request->input('id');
        $data = $request->validate(['id' => ['nullable', 'integer', Rule::exists($table, 'id')], 'title' => 'required|string|max:200', 'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique($table)->ignore($id)], 'summary' => 'required|string|max:2000', 'body' => 'required|string|max:50000', 'published' => 'nullable|boolean']);
        unset($data['id']);
        $data['published'] = $request->boolean('published');
        if ($type === 'report') {
            $data += $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'category' => ['required', Rule::in(config('stockedge.categories'))],
                'rating' => ['required', Rule::in(['Buy', 'Hold', 'Sell'])],
            ]);
            $data['premium'] = $request->boolean('premium');
            if ($id) {
                Report::where('id', $id)->update($data);
            } else {
                Report::create($data);
            }
        } else {
            $data += $request->validate(['topic' => ['required', Rule::in(config('stockedge.topics'))]]);
            if ($id) {
                Article::where('id', $id)->update($data);
            } else {
                Article::create($data);
            }
        }

        return back()->with('success', 'Content saved.');
    }

    public function delete(string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['report', 'article']), 404);
        ($type === 'report' ? Report::findOrFail($id) : Article::findOrFail($id))->delete();

        return back()->with('success', 'Content deleted.');
    }
}
