<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_page_lists_courses_with_student_count(): void
    {
        $course = Course::factory()->create();
        Student::factory()->count(2)->create(['course_id' => $course->id]);

        $response = $this->get('/courses');

        $response->assertOk();
        $response->assertSee($course->course_name);
        $response->assertSee('2');
    }

    public function test_course_show_page_lists_enrolled_students(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create(['course_id' => $course->id]);

        $response = $this->get("/courses/{$course->id}");

        $response->assertOk();
        $response->assertSee($course->course_name);
        $response->assertSee($student->full_name);
    }

    public function test_unknown_course_returns_404(): void
    {
        $this->get('/courses/999999')->assertNotFound();
    }

    public function test_valid_payload_creates_course_and_redirects_to_index(): void
    {
        $response = $this->post('/courses', $this->validPayload());

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', ['course_code' => 'BSMED']);
    }

    public function test_empty_payload_highlights_required_fields(): void
    {
        $response = $this->post('/courses', []);

        $response->assertSessionHasErrors(['course_code', 'course_name', 'status']);
    }

    #[DataProvider('invalidPayloads')]
    public function test_course_creation_rejects_invalid_input(string $field, mixed $value): void
    {
        $payload = $this->validPayload();
        $payload[$field] = $value;

        $response = $this->post('/courses', $payload);

        $response->assertSessionHasErrors($field);
    }

    public static function invalidPayloads(): array
    {
        return [
            'invalid status' => ['status', 'archived'],
        ];
    }

    public function test_course_creation_rejects_duplicate_course_code(): void
    {
        Course::factory()->create(['course_code' => 'BSMED']);

        $response = $this->post('/courses', $this->validPayload());

        $response->assertSessionHasErrors('course_code');
    }

    public function test_valid_payload_updates_course_and_redirects_to_index(): void
    {
        $course = Course::factory()->create();
        $payload = $this->validPayload($course->course_code);
        $payload['course_name'] = 'Updated Course Name';

        $response = $this->put("/courses/{$course->id}", $payload);

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'course_name' => 'Updated Course Name']);
    }

    public function test_deleting_course_removes_record_and_redirects_to_index(): void
    {
        $course = Course::factory()->create();

        $response = $this->delete("/courses/{$course->id}");

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success');
        $this->assertModelMissing($course);
    }

    public function test_deleting_course_removes_enrolled_students(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create(['course_id' => $course->id]);

        $this->delete("/courses/{$course->id}");

        $this->assertModelMissing($student);
    }

    private function validPayload(?string $code = null): array
    {
        return [
            'course_code' => $code ?? 'BSMED',
            'course_name' => 'Bachelor of Science in Medical Technology',
            'description' => 'A course focused on laboratory diagnostics.',
            'status' => 'active',
        ];
    }
}
