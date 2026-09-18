<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeoPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_only_published_cms_sections(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Research the business. Understand the investment.')
            ->assertSee('The latest company coverage.');

        DB::table('site_sections')->where('layout', 'research')->update(['published' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('The latest company coverage.');
    }

    public function test_article_seo_metadata_and_structured_data_use_editable_fields(): void
    {
        $this->seed();
        $article = Article::factory()->create([
            'title' => 'Market outlook',
            'seo_title' => 'Australian Market Outlook 2027',
            'meta_description' => 'A concise SharesRise outlook for Australian equities and long-term investors.',
        ]);

        $this->get(route('article', $article))
            ->assertOk()
            ->assertSee('<title>Australian Market Outlook 2027 | SharesRise</title>', false)
            ->assertSee('<meta name="description" content="A concise SharesRise outlook for Australian equities and long-term investors.">', false)
            ->assertSee('<link rel="canonical" href="'.route('article', $article).'">', false)
            ->assertSee('"@type":"Article"', false);
    }

    public function test_sitemap_contains_published_content_and_excludes_drafts(): void
    {
        $this->seed();
        $published = Article::factory()->create(['slug' => 'published-story', 'published' => true]);
        $draft = Article::factory()->create(['slug' => 'draft-story', 'published' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('article', $published), false)
            ->assertDontSee(route('article', $draft), false);

        $this->get(route('article', $draft))->assertNotFound();
    }

    public function test_robots_file_points_crawlers_to_the_dynamic_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.route('sitemap'), false)
            ->assertSee('Disallow: /admin', false);
    }
}
