<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_page_lists_students(): void
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin())->get('/admin/students');

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($student->student_id);
    }

    public function test_students_page_requires_authentication(): void
    {
        $this->get('/admin/students')->assertRedirectToRoute('login');
    }

    public function test_students_page_forbids_non_admin_roles(): void
    {
        $response = $this->actingAs($this->user(Role::Student))->get('/admin/students');

        $response->assertForbidden();
    }

    public function test_search_filters_students_by_name(): void
    {
        $course = Course::factory()->create();
        Student::factory()->create(['course_id' => $course->id, 'last_name' => 'Reynolds']);
        Student::factory()->create(['course_id' => $course->id, 'last_name' => 'Whitfield']);

        $response = $this->actingAs($this->admin())->get('/admin/students?search=Reynolds');

        $response->assertOk();
        $response->assertSee('Reynolds');
        $response->assertDontSee('Whitfield');
    }

    public function test_show_student_page_displays_details(): void
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin())->get("/admin/students/{$student->id}");

        $response->assertOk();
        $response->assertSee($student->full_name);
        $response->assertSee($student->email);
        $response->assertSee($student->address);
    }

    public function test_unknown_student_returns_404(): void
    {
        $this->actingAs($this->admin())->get('/admin/students/999999')->assertNotFound();
    }

    public function test_valid_payload_creates_student_and_redirects_to_show(): void
    {
        $course = Course::factory()->create();

        $response = $this->actingAs($this->admin())->post('/admin/students', $this->validPayload($course->id, 'new@example.com'));

        $student = Student::query()->where('email', 'new@example.com')->firstOrFail();

        $response->assertRedirect(route('students.show', $student));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('students', ['email' => 'new@example.com', 'course_id' => $course->id]);
    }

    public function test_valid_payload_with_login_creates_student_login(): void
    {
        $course = Course::factory()->create();
        $payload = $this->validPayload($course->id, 'new@example.com');
        $payload['create_account'] = '1';
        $payload['password'] = 'secret-password';
        $payload['password_confirmation'] = 'secret-password';

        $this->actingAs($this->admin())->post('/admin/students', $payload);

        $student = Student::query()->where('email', 'new@example.com')->firstOrFail();
        $this->assertNotNull($student->user);
        $this->assertSame('new@example.com', $student->user->email);
        $this->assertSame(Role::Student, $student->user->role);
        $this->assertTrue(password_verify('secret-password', $student->user->password));
    }

    public function test_creating_login_requires_confirmed_password(): void
    {
        $course = Course::factory()->create();
        $payload = $this->validPayload($course->id, 'new@example.com');
        $payload['create_account'] = '1';
        $payload['password'] = 'secret-password';
        $payload['password_confirmation'] = 'different-password';

        $response = $this->actingAs($this->admin())->post('/admin/students', $payload);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('students', ['email' => 'new@example.com']);
    }

    public function test_empty_payload_highlights_required_fields(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/students', []);

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

        $response = $this->actingAs($this->admin())->post('/admin/students', $payload);

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

        $response = $this->actingAs($this->admin())->post('/admin/students', $this->validPayload(
            $existing->course_id,
            $existing->email,
            $existing->student_id,
        ));

        $response->assertSessionHasErrors(['student_id', 'email']);
    }

    public function test_valid_payload_updates_student_and_redirects_to_show(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin())->put("/admin/students/{$student->id}", $this->validPayload(
            $course->id,
            $student->email,
            $student->student_id,
            'Updated',
            'Person',
        ));

        $response->assertRedirect(route('students.show', $student));
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

        $response = $this->actingAs($this->admin())->delete("/admin/students/{$student->id}");

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
        $this->assertModelMissing($student);
    }

    public function test_deleting_student_removes_linked_login(): void
    {
        $student = Student::factory()->withUser()->create();

        $this->actingAs($this->admin())->delete("/admin/students/{$student->id}");

        $this->assertModelMissing($student);
        $this->assertDatabaseMissing('users', ['id' => $student->user_id]);
    }

    public function test_updating_student_resets_linked_login_password(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->withUser()->create();
        $payload = $this->validPayload($course->id, $student->email, $student->student_id);
        $payload['reset_password'] = 'new-secret';

        $this->actingAs($this->admin())->put("/admin/students/{$student->id}", $payload);

        $this->assertTrue(password_verify('new-secret', $student->user->fresh()->password));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
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
