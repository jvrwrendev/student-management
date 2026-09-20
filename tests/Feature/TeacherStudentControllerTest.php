<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherStudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('teacher.students'))->assertRedirectToRoute('login');
    }

    public function test_admin_cannot_access_teacher_students(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($admin)->get(route('teacher.students'))->assertForbidden();
    }

    public function test_teacher_sees_only_assigned_students(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $otherTeacher = Teacher::factory()->withUser()->create();

        $assigned = Student::factory()->create(['first_name' => 'Anna', 'last_name' => 'Alpha']);
        $otherStudents = (object) [
            'assignedToOther' => Student::factory()->create(['first_name' => 'Bella', 'last_name' => 'Beta']),
            'unassigned' => Student::factory()->create(['first_name' => 'Cara', 'last_name' => 'Gamma']),
        ];

        $this->assign($assigned, $teacher);
        $this->assign($otherStudents->assignedToOther, $otherTeacher);

        $this->actingAs($teacher->user)
            ->get(route('teacher.students'))
            ->assertOk()
            ->assertSee($assigned->full_name)
            ->assertDontSee($otherStudents->assignedToOther->full_name)
            ->assertDontSee($otherStudents->unassigned->full_name);
    }

    public function test_teacher_sees_assigned_classes_for_students(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $student = Student::factory()->create(['first_name' => 'Anna', 'last_name' => 'Alpha']);

        $math = Subject::factory()->create(['subject_name' => 'Mathematics 101']);
        $science = Subject::factory()->create(['subject_name' => 'Science 101']);
        $this->assign($student, $teacher, $math);
        $this->assign($student, $teacher, $science, ['start_time' => '10:00']);

        $this->actingAs($teacher->user)
            ->get(route('teacher.students'))
            ->assertOk()
            ->assertSee('Mathematics 101')
            ->assertSee('Science 101');
    }

    public function test_search_filters_assigned_students(): void
    {
        $teacher = Teacher::factory()->withUser()->create();

        $assigned = Student::factory()->create(['first_name' => 'Anna', 'last_name' => 'Alpha', 'student_id' => 'STU-1001']);
        $other = Student::factory()->create(['first_name' => 'Bella', 'last_name' => 'Beta', 'student_id' => 'STU-1002']);
        $this->assign($assigned, $teacher);
        $this->assign($other, $teacher);

        $this->actingAs($teacher->user)
            ->get(route('teacher.students', ['search' => 'Alpha']))
            ->assertOk()
            ->assertSee($assigned->full_name)
            ->assertDontSee($other->full_name);
    }

    private function assign(Student $student, Teacher $teacher, ?Subject $subject = null, array $overrides = []): void
    {
        Schedule::factory()->create(array_merge([
            'student_id' => $student->id,
            'subject_id' => ($subject ?? Subject::factory()->create())->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ], $overrides));
    }
}
