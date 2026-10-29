<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroBannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_and_apply_a_homepage_background(): void
    {
        Storage::fake('public');
        $this->seed(AdminContentSeeder::class);
        $section = DB::table('site_sections')->where('layout', 'hero')->first();
        $this->actingAs(User::factory()->admin()->create())->post(route('admin.media.upload'), [
            'name' => 'Bull and bear banner', 'alt_text' => 'Bull and bear market illustration',
            'section_id' => $section->id, 'banner_type' => 'web', 'file' => UploadedFile::fake()->image('banner.png', 1916, 821),
        ])->assertRedirect(route('admin.edit', ['sections', $section->id]))->assertSessionHasNoErrors();
        $path = DB::table('site_sections')->where('id', $section->id)->value('image_path');
        Storage::disk('public')->assertExists($path);
        $this->get(route('home'))->assertOk()->assertSee(route('media.file', basename($path)), false)
            ->assertSee('sr-hero-background')->assertDontSee('SharesRise research workspace')->assertDontSee('class="sr-device-screen"', false);
        $this->get(route('media.file', basename($path)))->assertOk();
    }

    public function test_mobile_and_web_banners_can_be_replaced_independently(): void
    {
        Storage::fake('public');
        $this->seed(AdminContentSeeder::class);
        $section = DB::table('site_sections')->where('layout', 'hero')->first();
        $this->actingAs(User::factory()->admin()->create());
        $paths = [];
        foreach (['web', 'mobile', 'web'] as $type) {
            $this->post(route('admin.media.upload'), [
                'name' => 'Homepage '.$type, 'alt_text' => $type.' banner', 'section_id' => $section->id,
                'banner_type' => $type, 'file' => UploadedFile::fake()->image($type.'.png', 800, 1200),
            ])->assertSessionHasNoErrors()->assertRedirect();
            $current = DB::table('site_sections')->find($section->id);
            $paths[$type] = $type === 'web' ? $current->image_path : $current->mobile_image_path;
            if (isset($paths['mobile'])) {
                $this->assertSame($paths['mobile'], $current->mobile_image_path);
                $this->assertSame($paths['web'], $current->image_path);
            }
        }
        $this->get(route('home'))->assertOk()
            ->assertSee('media="(max-width: 767px)"', false)
            ->assertSee('srcset="'.route('media.file', basename($paths['mobile'])).'"', false)
            ->assertSee('src="'.route('media.file', basename($paths['web'])).'"', false);
        $this->get(route('admin.edit', ['sections', $section->id]))->assertOk()
            ->assertSee('Banner type')->assertSee('Current web banner')->assertSee('Current mobile banner');
    }

    public function test_banner_type_must_be_web_or_mobile(): void
    {
        Storage::fake('public');
        $this->seed(AdminContentSeeder::class);
        $section = DB::table('site_sections')->where('layout', 'hero')->first();
        $this->actingAs(User::factory()->admin()->create());
        foreach ([null, 'tablet'] as $type) {
            $this->post(route('admin.media.upload'), [
                'name' => 'Banner', 'alt_text' => 'Banner', 'section_id' => $section->id,
                'banner_type' => $type, 'file' => UploadedFile::fake()->image('banner.png'),
            ])->assertSessionHasErrors('banner_type');
        }
        $this->assertDatabaseCount('media_assets', 0);
    }

    public function test_banner_upload_rejects_non_images_and_non_hero_sections(): void
    {
        Storage::fake('public');
        $this->seed(AdminContentSeeder::class);
        $section = DB::table('site_sections')->where('layout', 'market')->first();
        $this->actingAs(User::factory()->admin()->create())->post(route('admin.media.upload'), [
            'name' => 'Invalid banner', 'alt_text' => 'Invalid', 'section_id' => $section->id,
            'file' => UploadedFile::fake()->create('banner.txt', 1, 'text/plain'),
        ])->assertSessionHasErrors(['file', 'section_id']);
        $this->assertDatabaseCount('media_assets', 0);
    }

    public function test_members_cannot_upload_a_banner(): void
    {
        $this->actingAs(User::factory()->create())->post(route('admin.media.upload'))->assertForbidden();
    }
}
