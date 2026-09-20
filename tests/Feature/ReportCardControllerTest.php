<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_student_report_card(): void
    {
        $student = Student::factory()->create();
        $grade = Grade::factory()->create([
            'student_id' => $student->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);

        $response = $this->actingAs($this->user(Role::Admin))->get("/report-cards/{$student->id}");

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($grade->subject->subject_name);
        $response->assertSee($grade->grade);
    }

    public function test_teacher_can_view_student_report_card(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $student = Student::factory()->create();

        $this->actingAs($teacher->user)->get("/report-cards/{$student->id}")->assertOk();
    }

    public function test_term_selector_limits_grades_to_selected_term(): void
    {
        $student = Student::factory()->create();
        $firstSemester = Grade::factory()->create([
            'student_id' => $student->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);
        Grade::factory()->create([
            'student_id' => $student->id,
            'semester' => '2nd Semester',
            'academic_year' => '2026-2027',
        ]);

        $response = $this->actingAs($this->user(Role::Admin))
            ->get("/report-cards/{$student->id}?semester=1st+Semester&academic_year=2026-2027");

        $response->assertSee($firstSemester->subject->subject_name);
        $response->assertSee('Subjects');
    }

    public function test_report_card_with_no_grades_shows_empty_state(): void
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->user(Role::Admin))->get("/report-cards/{$student->id}");

        $response->assertOk();
        $response->assertSee('No grades recorded for this term.');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $student = Student::factory()->create();

        $this->get("/report-cards/{$student->id}")->assertRedirectToRoute('login');
    }

    public function test_student_role_is_forbidden(): void
    {
        $student = Student::factory()->create();
        $user = User::factory()->create(['role' => Role::Student]);

        $this->actingAs($user)->get("/report-cards/{$student->id}")->assertForbidden();
    }

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
    }
}
