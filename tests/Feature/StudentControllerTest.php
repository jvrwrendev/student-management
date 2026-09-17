<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_page_lists_students(): void
    {
        $student = Student::factory()->create();

        $response = $this->get('/students');

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($student->student_id);
    }

    public function test_search_filters_students_by_name(): void
    {
        $course = Course::factory()->create();
        Student::factory()->create(['course_id' => $course->id, 'last_name' => 'Reynolds']);
        Student::factory()->create(['course_id' => $course->id, 'last_name' => 'Whitfield']);

        $response = $this->get('/students?search=Reynolds');

        $response->assertOk();
        $response->assertSee('Reynolds');
        $response->assertDontSee('Whitfield');
    }

    public function test_show_student_page_displays_details(): void
    {
        $student = Student::factory()->create();

        $response = $this->get("/students/{$student->id}");

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($student->email);
        $response->assertSee($student->address);
    }

    public function test_unknown_student_returns_404(): void
    {
        $this->get('/students/999999')->assertNotFound();
    }

    public function test_valid_payload_creates_student_and_redirects_to_index(): void
    {
        $course = Course::factory()->create();

        $response = $this->post('/students', $this->validPayload($course->id, 'new@example.com'));

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('students', ['email' => 'new@example.com', 'course_id' => $course->id]);
    }

    public function test_empty_payload_highlights_required_fields(): void
    {
        $response = $this->post('/students', []);

        $response->assertSessionHasErrors([
            'student_id',
            'first_name',
            'last_name',
            'gender',
            'date_of_birth',
            'email',
            'address',
            'course_id',
            'year_level',
            'status',
        ]);
    }

    #[DataProvider('invalidPayloads')]
    public function test_student_creation_rejects_invalid_input(string $field, mixed $value): void
    {
        $course = Course::factory()->create();
        $payload = $this->validPayload($course->id, 'new@example.com');
        $payload[$field] = $value;

        $response = $this->post('/students', $payload);

        $response->assertSessionHasErrors($field);
    }

    public static function invalidPayloads(): array
    {
        return [
            'invalid email format' => ['email', 'not-an-email'],
            'unknown course' => ['course_id', 999999],
            'invalid year level' => ['year_level', 'Grade 12'],
            'invalid gender' => ['gender', 'Unknown'],
            'invalid status' => ['status', 'pending'],
            'birth date in the future' => ['date_of_birth', now()->addDay()->toDateString()],
        ];
    }

    public function test_student_creation_rejects_duplicate_student_id_and_email(): void
    {
        $existing = Student::factory()->create();

        $response = $this->post('/students', $this->validPayload(
            $existing->course_id,
            $existing->email,
            $existing->student_id,
        ));

        $response->assertSessionHasErrors(['student_id', 'email']);
    }

    public function test_valid_payload_updates_student_and_redirects_to_index(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->put("/students/{$student->id}", $this->validPayload(
            $course->id,
            $student->email,
            $student->student_id,
            'Updated',
            'Person',
        ));

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Updated',
            'last_name' => 'Person',
            'course_id' => $course->id,
        ]);
    }

    public function test_deleting_student_removes_record_and_redirects_to_index(): void
    {
        $student = Student::factory()->create();

        $response = $this->delete("/students/{$student->id}");

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
        $this->assertModelMissing($student);
    }

    private function validPayload(
        int $courseId,
        string $email,
        ?string $studentId = null,
        string $firstName = 'Jane',
        string $lastName = 'Doe',
        string $yearLevel = '2nd Year',
    ): array {
        return [
            'student_id' => $studentId ?? 'STU-5555',
            'first_name' => $firstName,
            'middle_name' => 'A',
            'last_name' => $lastName,
            'gender' => 'Female',
            'date_of_birth' => '2005-05-10',
            'email' => $email,
            'phone' => '+1 555 0100',
            'address' => '112 Main Street, Springfield',
            'course_id' => $courseId,
            'year_level' => $yearLevel,
            'status' => 'active',
        ];
    }
}
