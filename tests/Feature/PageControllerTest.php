<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Post::create([
            'title' => 'First Post',
            'slug' => 'first-post',
            'content' => 'Content of the first post.',
        ]);
    }

    public function test_about_page_renders_team_members(): void
    {
        $response = $this->actingAs($this->user())->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Alex Morgan');
    }

    public function test_contact_page_renders_contact_details(): void
    {
        $response = $this->actingAs($this->user())->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('hello@example.com');
    }

    public function test_post_show_page_displays_full_content(): void
    {
        $response = $this->actingAs($this->user())->get('/posts/first-post');

        $response->assertStatus(200);
        $response->assertSee('Content of the first post.');
        $response->assertSee('First Post');
    }

    public function test_unknown_post_returns_404(): void
    {
        $this->actingAs($this->user())->get('/posts/does-not-exist')->assertNotFound();
    }

    public function test_public_pages_require_authentication(): void
    {
        $this->get('/about')->assertRedirectToRoute('login');
    }

    private function user(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }
}
