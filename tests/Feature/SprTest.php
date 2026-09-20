<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SprTest extends TestCase
{
    use RefreshDatabase;

    public function test_spr_shows_grades_grouped_by_academic_year_and_semester_with_units(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create(['subject_name' => 'Data Structures', 'units' => 4.0]);
        $teacher = Teacher::factory()->create();
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 1.5,
        ]);

        $response = $this->actingAs($this->admin())->get("/admin/students/{$student->id}/spr");

        $response->assertOk()
            ->assertSee('School Year 2026-2027')
            ->assertSee('1st Semester')
            ->assertSee('Data Structures')
            ->assertSee('4.0');
    }

    public function test_spr_awards_honors_when_percentage_meets_threshold(): void
    {
        Setting::set('honors_threshold', '90.00');

        $student = Student::factory()->create();
        $subject = Subject::factory()->create(['units' => 3.0]);
        $teacher = Teacher::factory()->create();
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 1.5,
            'remarks' => 'Excellent',
        ]);

        $response = $this->actingAs($this->admin())->get("/admin/students/{$student->id}/spr");

        $response->assertOk()->assertSee('With Honors');
    }

    public function test_spr_omits_honors_below_threshold(): void
    {
        Setting::set('honors_threshold', '96.00');

        $student = Student::factory()->create();
        $subject = Subject::factory()->create(['units' => 3.0]);
        $teacher = Teacher::factory()->create();
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 2.25,
        ]);

        $response = $this->actingAs($this->admin())->get("/admin/students/{$student->id}/spr");

        $response->assertOk()->assertDontSee('With Honors');
    }

    public function test_spr_from_subject_with_units_weights_average(): void
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $subjectA = Subject::factory()->create(['units' => 3.0]);
        $subjectB = Subject::factory()->create(['units' => 1.0]);
        $teacher = Teacher::factory()->create();
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subjectA->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 2.0,
        ]);
        Grade::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subjectB->id,
            'teacher_id' => $teacher->id,
            'semester' => '1st Semester',
            'academic_year' => '2026-2027',
            'grade' => 4.0,
        ]);

        $response = $this->actingAs($this->admin())->get("/admin/students/{$student->id}/spr");

        $weightedGwa = round((2.0 * 3.0 + 4.0 * 1.0) / 4.0, 2);

        $response->assertOk()->assertSee(number_format($weightedGwa, 2));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }
}
