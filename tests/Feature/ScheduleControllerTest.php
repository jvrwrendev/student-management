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

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_schedule_and_redirects_to_index(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($this->admin())->post('/schedules', $this->validPayload($student, $subject, $teacher));

        $response->assertRedirect(route('schedules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('schedules', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'day' => 'Monday',
            'start_time' => '08:00',
        ]);
    }

    public function test_index_requires_authentication(): void
    {
        $this->get('/schedules')->assertRedirectToRoute('login');
    }

    public function test_creation_rejects_end_time_before_start_time(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->create();
        $payload = $this->validPayload($student, $subject, $teacher);
        $payload['start_time'] = '10:00';
        $payload['end_time'] = '08:00';

        $response = $this->actingAs($this->admin())->post('/schedules', $payload);

        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseCount('schedules', 0);
    }

    public function test_creation_rejects_conflict_for_same_student(): void
    {
        $student = Student::factory()->create();
        $subjectA = Subject::factory()->create();
        $subjectB = Subject::factory()->create();
        $teacherA = Teacher::factory()->create();
        $teacherB = Teacher::factory()->create();

        Schedule::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subjectA->id,
            'teacher_id' => $teacherA->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);

        $response = $this->actingAs($this->admin())->post('/schedules', $this->validPayload($student, $subjectB, $teacherB));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('schedules', 1);
    }

    public function test_creation_rejects_conflict_for_same_teacher(): void
    {
        $studentA = Student::factory()->create();
        $studentB = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->create();

        Schedule::factory()->create([
            'student_id' => $studentA->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);

        $response = $this->actingAs($this->admin())->post('/schedules', $this->validPayload($studentB, $subject, $teacher));

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('schedules', 1);
    }

    public function test_creation_accepts_non_conflicting_schedule(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->create();

        Schedule::factory()->create();

        $response = $this->actingAs($this->admin())->post('/schedules', $this->validPayload($student, $subject, $teacher));

        $response->assertRedirect(route('schedules.index'));
        $this->assertDatabaseCount('schedules', 2);
    }

    public function test_teacher_schedule_conflict_blocks_update(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $subject = Subject::factory()->create();
        $student = Student::factory()->create();

        $existing = Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);
        $target = Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ]);

        $payload = $this->validPayload($student, $subject, $teacher);
        $payload['start_time'] = $existing->start_time;
        $payload['end_time'] = $existing->end_time;
        unset($payload['teacher_id']);

        $response = $this->actingAs($teacher->user)->put("/schedules/{$target->id}", $payload);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('schedules', ['id' => $target->id, 'start_time' => $target->start_time]);
    }

    public function test_teacher_cannot_delete_another_teachers_schedule(): void
    {
        $otherTeacher = Teacher::factory()->withUser()->create();
        $schedule = Schedule::factory()->create(['teacher_id' => $otherTeacher->id]);
        $teacher = Teacher::factory()->withUser()->create();

        $response = $this->actingAs($teacher->user)->delete("/schedules/{$schedule->id}");

        $response->assertForbidden();
        $this->assertModelExists($schedule);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }

    private function validPayload(Student $student, Subject $subject, Teacher $teacher): array
    {
        return [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room' => 'Room 101',
            'day' => 'Monday',
            'start_time' => '08:00',
            'end_time' => '09:30',
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
        ];
    }
}
