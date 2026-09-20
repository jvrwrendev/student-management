<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_profile(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_profile_page(): void
    {
        $user = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    public function test_admin_can_update_name_and_email(): void
    {
        $user = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Renamed Admin',
                'email' => 'renamed-admin@example.com',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Renamed Admin',
            'email' => 'renamed-admin@example.com',
        ]);
    }

    public function test_admin_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create(['role' => Role::Admin, 'password' => 'old-password']);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'current_password' => 'old-password',
                'password' => 'new-secret-password',
                'password_confirmation' => 'new-secret-password',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('new-secret-password', $user->fresh()->password));
    }

    public function test_password_change_requires_current_password(): void
    {
        $user = User::factory()->create(['role' => Role::Admin, 'password' => 'old-password']);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'current_password' => 'wrong-password',
                'password' => 'new-secret-password',
                'password_confirmation' => 'new-secret-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_blank_password_keeps_existing_password(): void
    {
        $user = User::factory()->create(['role' => Role::Admin, 'password' => 'old-password']);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_email_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => Role::Admin]);
        $other = User::factory()->create();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $other->email,
            ])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => $user->email]);
    }

    public function test_teacher_can_update_phone_and_department(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $user = $teacher->user->fresh();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '09171234567',
                'department' => 'College of Information Technology',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'phone' => '09171234567',
            'department' => 'College of Information Technology',
        ]);
    }

    public function test_student_can_update_phone_and_address(): void
    {
        $student = Student::factory()->withUser()->create();
        $user = $student->user->fresh();
        $originalStudentId = $student->student_id;
        $originalCourseId = $student->course_id;
        $originalYearLevel = $student->year_level;

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '09171234567',
                'address' => '123 Mabini Street, Manila',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $student->refresh();

        $this->assertSame('09171234567', $student->phone);
        $this->assertSame('123 Mabini Street, Manila', $student->address);
        $this->assertSame($originalStudentId, $student->student_id);
        $this->assertSame($originalCourseId, $student->course_id);
        $this->assertSame($originalYearLevel, $student->year_level);
    }
}
