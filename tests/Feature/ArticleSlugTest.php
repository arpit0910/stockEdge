<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ArticleSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_slugs_are_generated_from_titles_and_disambiguated(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $data = ['title' => 'Market Outlook!', 'slug' => 'custom-address', 'topic' => config('stockedge.topics')[0], 'summary' => 'Summary', 'body' => 'Article body', 'published' => false];
        foreach (['market-outlook', 'market-outlook-2', 'market-outlook-3'] as $expected) {
            $this->post(route('admin.store', 'articles'), $data)->assertSessionHasNoErrors()->assertRedirect();
            $this->assertDatabaseHas('articles', ['title' => 'Market Outlook!', 'slug' => $expected]);
        }
        $this->assertDatabaseMissing('articles', ['slug' => 'custom-address']);
        $this->get(route('admin.create', 'articles'))->assertOk()->assertDontSee('name="slug"', false);
        $article = Article::where('slug', 'market-outlook-2')->firstOrFail();
        $data['_version'] = DB::table('articles')->where('id', $article->id)->value('updated_at');
        $this->put(route('admin.update', ['articles', $article->id]), $data)->assertSessionHasNoErrors();
        $this->assertSame('market-outlook-2', $article->fresh()->slug);
        $data['title'] = 'New Company Outlook';
        $data['_version'] = DB::table('articles')->where('id', $article->id)->value('updated_at');
        $this->put(route('admin.update', ['articles', $article->id]), $data)->assertSessionHasNoErrors();
        $this->assertSame('new-company-outlook', $article->fresh()->slug);
        $this->get(route('admin.edit', ['articles', $article->id]))->assertOk()->assertDontSee('name="slug"', false);
    }

    public function test_legacy_article_editor_also_ignores_custom_slugs(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->post(route('admin.save', 'article'), [
            'title' => 'Legacy Article Title', 'slug' => 'custom-slug', 'topic' => config('stockedge.topics')[0],
            'summary' => 'Summary', 'body' => 'Body',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('articles', ['slug' => 'legacy-article-title']);
    }

    public function test_title_without_slug_characters_is_rejected(): void
    {
        $this->actingAs(User::factory()->admin()->create())->post(route('admin.store', 'articles'), [
            'title' => '!!!', 'topic' => config('stockedge.topics')[0], 'summary' => 'Summary', 'body' => 'Body',
        ])->assertSessionHasErrors('title');
        $this->assertDatabaseCount('articles', 0);
    }
}
