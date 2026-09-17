<?php

namespace Tests\Feature;

use App\Models\Post;
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
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Alex Morgan');
    }

    public function test_contact_page_renders_contact_details(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('hello@example.com');
    }

    public function test_post_show_page_displays_full_content(): void
    {
        $response = $this->get('/posts/first-post');

        $response->assertStatus(200);
        $response->assertSee('Content of the first post.');
        $response->assertSee('First Post');
    }

    public function test_unknown_post_returns_404(): void
    {
        $this->get('/posts/does-not-exist')->assertNotFound();
    }
}
