<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserLoginCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_login_for_student(): void
    {
        $student = Student::factory()->create();

        $this->artisan('app:create-login', ['model' => 'student', 'id' => $student->id, '--password' => 'secret-password'])
            ->expectsOutputToContain('Login created')
            ->assertExitCode(0);

        $this->assertNotNull($student->fresh()->user);
        $this->assertSame(Role::Student, $student->fresh()->user->role);
        $this->assertSame($student->email, $student->fresh()->user->email);
        $this->assertTrue(password_verify('secret-password', $student->fresh()->user->password));
    }

    public function test_command_creates_login_for_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $this->artisan('app:create-login', ['model' => 'teacher', 'id' => $teacher->id])
            ->expectsOutputToContain('Login created')
            ->assertExitCode(0);

        $this->assertNotNull($teacher->fresh()->user);
        $this->assertSame(Role::Teacher, $teacher->fresh()->user->role);
    }

    public function test_command_rejects_student_that_already_has_login(): void
    {
        $student = Student::factory()->withUser()->create();

        $this->artisan('app:create-login', ['model' => 'student', 'id' => $student->id])
            ->expectsOutputToContain('already has a login')
            ->assertExitCode(1);
    }

    public function test_command_returns_failure_for_unknown_model(): void
    {
        $this->artisan('app:create-login', ['model' => 'guardian', 'id' => 1])
            ->assertExitCode(1);
    }

    public function test_command_returns_failure_for_missing_record(): void
    {
        $this->artisan('app:create-login', ['model' => 'student', 'id' => 999999])
            ->assertExitCode(1);
    }
}
