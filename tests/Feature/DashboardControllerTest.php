<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_statistics_and_recent_students(): void
    {
        $course = Course::factory()->create();
        $activeStudent = Student::factory()->create(['course_id' => $course->id, 'status' => 'active']);
        Student::factory()->create(['course_id' => $course->id, 'status' => 'inactive']);

        $response = $this->actingAs($this->admin())->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Total Students');
        $response->assertSee('Active Students');
        $response->assertSee('Inactive Students');
        $response->assertSee('Total Courses');
        $response->assertSee('Recent Students');
        $response->assertSee($activeStudent->full_name);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirectToRoute('login');
    }

    public function test_role_home_redirects_admin_to_dashboard(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_role_home_redirects_student_to_portal(): void
    {
        $user = $this->user(Role::Student);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('portal.dashboard'));
    }

    public function test_role_home_redirects_teacher_to_dashboard(): void
    {
        $user = $this->user(Role::Teacher);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('teacher.dashboard'));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
    }
}
