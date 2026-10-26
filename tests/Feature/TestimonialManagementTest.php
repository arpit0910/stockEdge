<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_a_testimonial_without_an_occupation(): void
    {
        $admin = User::factory()->admin()->create();
        $data = Testimonial::factory()->make(['name' => 'Alex Morgan', 'role' => null])->toArray();
        unset($data['role']);

        $this->actingAs($admin)->post('/admin/manage/testimonials', $data)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('testimonials', ['name' => 'Alex Morgan', 'role' => null]);
        $this->get('/')->assertOk()->assertSee('Alex Morgan')->assertSee($data['quote']);
    }

    public function test_testimonial_card_omits_empty_roles_and_escapes_client_content(): void
    {
        $testimonial = Testimonial::factory()->make([
            'name' => 'Alex Morgan',
            'role' => null,
            'quote' => '<script>alert("quote")</script>',
        ]);

        $this->blade('<x-testimonial-card :testimonial="$testimonial" />', compact('testimonial'))
            ->assertSee('Alex Morgan')
            ->assertSee($testimonial->quote)
            ->assertDontSee('<script>', false)
            ->assertDontSee('<small>', false);

        $testimonial->role = 'Teacher';
        $this->blade('<x-testimonial-card :testimonial="$testimonial" />', compact('testimonial'))
            ->assertSee('Teacher');
    }
}
