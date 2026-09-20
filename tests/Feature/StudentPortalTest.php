<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_dashboard_renders_profile_and_stats(): void
    {
        $student = Student::factory()->withUser()->create();
        Grade::factory()->count(3)->create(['student_id' => $student->id]);

        $response = $this->actingAs($student->user)->get('/portal');

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($student->course->course_name);
        $response->assertSee('Average');
        $response->assertSee('3');
    }

    public function test_student_grades_page_lists_only_own_grades(): void
    {
        $student = Student::factory()->withUser()->create();
        $ownGrade = Grade::factory()->create(['student_id' => $student->id]);
        $otherStudent = Student::factory()->withUser()->create();
        $otherGrade = Grade::factory()->create(['student_id' => $otherStudent->id]);

        $response = $this->actingAs($student->user)->get('/portal/grades');

        $response->assertOk();
        $response->assertSee($ownGrade->subject->subject_name);
        $response->assertDontSee($otherGrade->subject->subject_name);
    }

    public function test_student_schedule_page_lists_assigned_classes(): void
    {
        $student = Student::factory()->withUser()->create();
        $schedule = Schedule::factory()->create(['student_id' => $student->id]);

        $response = $this->actingAs($student->user)->get('/portal/schedule');

        $response->assertOk();
        $response->assertSee($schedule->subject->subject_name);
        $response->assertSee($schedule->teacher->full_name);
    }

    public function test_student_without_profile_is_forbidden(): void
    {
        $user = User::factory()->create(['role' => Role::Student]);

        $this->actingAs($user)->get('/portal')->assertForbidden();
    }

    public function test_admin_cannot_access_student_portal(): void
    {
        $user = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($user)->get('/portal')->assertForbidden();
    }
}
