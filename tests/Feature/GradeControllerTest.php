<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GradeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_creates_grade_for_assigned_class_and_redirects_to_index(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->withUser()->create();
        $this->createSchedule($student, $subject, $teacher);

        $response = $this->actingAs($teacher->user)->post('/grades', $this->validPayload($student, $subject));

        $response->assertRedirect(route('grades.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('grades', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'grade' => 1.5,
        ]);
    }

    public function test_admin_cannot_access_grades_area(): void
    {
        $this->actingAs($this->user(Role::Admin))->get('/grades')->assertForbidden();
    }

    public function test_index_requires_authentication(): void
    {
        $this->get('/grades')->assertRedirectToRoute('login');
    }

    #[DataProvider('invalidGrades')]
    public function test_creation_rejects_invalid_grade(float|string $grade): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->withUser()->create();
        $this->createSchedule($student, $subject, $teacher);
        $payload = $this->validPayload($student, $subject);
        $payload['grade'] = $grade;

        $response = $this->actingAs($teacher->user)->post('/grades', $payload);

        $response->assertSessionHasErrors('grade');
        $this->assertDatabaseCount('grades', 0);
    }

    public static function invalidGrades(): array
    {
        return [
            'non-increment' => [1.2],
            'below minimum' => [0.75],
            'above maximum' => [5.5],
            'not numeric' => ['excellent'],
        ];
    }

    public function test_creation_accepts_valid_increments(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->withUser()->create();
        $this->createSchedule($student, $subject, $teacher);
        $payload = $this->validPayload($student, $subject);
        $payload['grade'] = 2.75;

        $response = $this->actingAs($teacher->user)->post('/grades', $payload);

        $response->assertRedirect(route('grades.index'));
        $this->assertDatabaseHas('grades', ['grade' => 2.75]);
    }

    public function test_creation_rejects_duplicate_student_subject_term(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->withUser()->create();
        $this->createSchedule($student, $subject, $teacher);
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);

        $response = $this->actingAs($teacher->user)->post('/grades', $this->validPayload($student, $subject));

        $response->assertSessionHasErrors('student_id');
        $this->assertDatabaseCount('grades', 1);
    }

    public function test_teacher_records_grade_only_for_assigned_class(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($teacher->user)->post('/grades', $this->validPayload($student, $subject));

        $response->assertSessionHasErrors('student_id');
        $this->assertDatabaseCount('grades', 0);
    }

    public function test_teacher_records_grade_for_assigned_class(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $this->createSchedule($student, $subject, $teacher);

        $response = $this->actingAs($teacher->user)->post('/grades', $this->validPayload($student, $subject));

        $response->assertRedirect(route('grades.index'));
        $this->assertDatabaseHas('grades', [
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function test_teacher_cannot_edit_another_teachers_grade(): void
    {
        $otherTeacher = Teacher::factory()->withUser()->create();
        $grade = Grade::factory()->create(['teacher_id' => $otherTeacher->id]);
        $teacher = Teacher::factory()->withUser()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($teacher->user)->put("/grades/{$grade->id}", $this->validPayload($student, $subject));

        $response->assertForbidden();
    }

    public function test_teacher_edits_owned_grade(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $grade = Grade::factory()->create(['teacher_id' => $teacher->id]);
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $this->createSchedule($student, $subject, $teacher);
        $payload = $this->validPayload($student, $subject);
        $payload['grade'] = 2.25;

        $response = $this->actingAs($teacher->user)->put("/grades/{$grade->id}", $payload);

        $response->assertRedirect(route('grades.index'));
        $this->assertDatabaseHas('grades', ['id' => $grade->id, 'grade' => 2.25]);
    }

    public function test_teacher_cannot_delete_another_teachers_grade(): void
    {
        $otherTeacher = Teacher::factory()->withUser()->create();
        $grade = Grade::factory()->create(['teacher_id' => $otherTeacher->id]);
        $teacher = Teacher::factory()->withUser()->create();

        $response = $this->actingAs($teacher->user)->delete("/grades/{$grade->id}");

        $response->assertForbidden();
        $this->assertModelExists($grade);
    }

    private function createSchedule(Student $student, Subject $subject, Teacher $teacher): void
    {
        Schedule::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);
    }

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function validPayload(Student $student, Subject $subject): array
    {
        return [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 1.5,
            'remarks' => 'Excellent',
        ];
    }
}
