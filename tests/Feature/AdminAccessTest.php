<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('nonAdminRoles')]
    public function test_admin_area_forbids_non_admin_roles(Role $role): void
    {
        $user = $this->userWithProfile($role);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/admin/students')->assertForbidden();
        $this->actingAs($user)->get('/admin/teachers')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin/dashboard')->assertRedirectToRoute('login');
    }

    public function test_teacher_can_access_grades_and_teacher_dashboard(): void
    {
        $teacher = Teacher::factory()->withUser()->create();

        $this->actingAs($teacher->user)->get('/grades')->assertOk();
        $this->actingAs($teacher->user)->get('/schedules')->assertOk();
        $this->actingAs($teacher->user)->get('/teacher/dashboard')->assertOk();
    }

    public function test_admin_can_access_teacher_dashboard(): void
    {
        $this->actingAs($this->user(Role::Admin))->get('/teacher/dashboard')->assertForbidden();
    }

    public function test_student_cannot_access_grades_area(): void
    {
        $student = Student::factory()->withUser()->create();

        $this->actingAs($student->user)->get('/grades')->assertForbidden();
        $this->actingAs($student->user)->get('/schedules')->assertForbidden();
    }

    public function test_teacher_cannot_access_student_portal(): void
    {
        $teacher = Teacher::factory()->withUser()->create();

        $this->actingAs($teacher->user)->get('/portal')->assertForbidden();
    }

    public function test_admin_can_access_spr_and_settings(): void
    {
        $student = Student::factory()->create();
        $admin = $this->userWithProfile(Role::Admin);

        $this->actingAs($admin)->get("/admin/students/{$student->id}/spr")->assertOk();
        $this->actingAs($admin)->get('/admin/settings')->assertOk();
    }

    public function test_teacher_cannot_access_spr(): void
    {
        $student = Student::factory()->create();
        $teacher = Teacher::factory()->withUser()->create();

        $this->actingAs($teacher->user)->get("/admin/students/{$student->id}/spr")->assertForbidden();
    }

    public function test_admin_can_update_honors_threshold(): void
    {
        Setting::set('honors_threshold', '90.00');

        $this->actingAs($this->userWithProfile(Role::Admin))
            ->put('/admin/settings', ['honors_threshold' => 91.5])
            ->assertRedirect(route('settings.index'))
            ->assertSessionHas('success');

        $this->assertSame('91.50', Setting::value('honors_threshold'));
    }

    public function test_guest_is_redirected_to_login_from_portal(): void
    {
        $this->get('/portal')->assertRedirectToRoute('login');
    }

    public static function nonAdminRoles(): array
    {
        return collect(Role::cases())
            ->reject(fn (Role $role): bool => $role === Role::Admin)
            ->mapWithKeys(fn (Role $role): array => [$role->value => [$role]])
            ->all();
    }

    private function userWithProfile(Role $role): User
    {
        return match ($role) {
            Role::Teacher => Teacher::factory()->withUser()->create()->user,
            Role::Student => Student::factory()->withUser()->create()->user,
            default => $this->user($role),
        };
    }

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
    }
}
