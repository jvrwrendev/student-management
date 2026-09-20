<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $this->get('/login')->assertOk()->assertSee('Sign in');
    }

    public function test_admin_can_log_in_and_lands_on_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => Role::Admin, 'password' => 'secret-password']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_student_can_log_in_and_lands_on_portal(): void
    {
        $user = User::factory()->create(['role' => Role::Student, 'password' => 'secret-password']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_wrong_credentials(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
