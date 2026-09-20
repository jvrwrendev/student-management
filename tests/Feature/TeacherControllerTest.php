<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teachers_page_lists_teachers(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($this->admin())->get('/admin/teachers');

        $response->assertOk();
        $response->assertSee($teacher->full_name);
        $response->assertSee($teacher->department);
    }

    public function test_valid_payload_creates_teacher_and_redirects_to_show(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/teachers', $this->validPayload('new@example.com'));

        $teacher = Teacher::query()->where('email', 'new@example.com')->firstOrFail();

        $response->assertRedirect(route('teachers.show', $teacher));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', ['email' => 'new@example.com']);
    }

    public function test_valid_payload_with_login_creates_teacher_login(): void
    {
        $payload = $this->validPayload('new@example.com');
        $payload['create_account'] = '1';
        $payload['password'] = 'secret-password';
        $payload['password_confirmation'] = 'secret-password';

        $this->actingAs($this->admin())->post('/admin/teachers', $payload);

        $teacher = Teacher::query()->where('email', 'new@example.com')->firstOrFail();
        $this->assertNotNull($teacher->user);
        $this->assertSame('new@example.com', $teacher->user->email);
        $this->assertSame(Role::Teacher, $teacher->user->role);
        $this->assertTrue(password_verify('secret-password', $teacher->user->password));
    }

    public function test_creating_login_requires_confirmed_password(): void
    {
        $payload = $this->validPayload('new@example.com');
        $payload['create_account'] = '1';
        $payload['password'] = 'secret-password';
        $payload['password_confirmation'] = 'different-password';

        $response = $this->actingAs($this->admin())->post('/admin/teachers', $payload);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('teachers', ['email' => 'new@example.com']);
    }

    public function test_creating_login_without_password_is_rejected(): void
    {
        $payload = $this->validPayload('new@example.com');
        $payload['create_account'] = '1';

        $response = $this->actingAs($this->admin())->post('/admin/teachers', $payload);

        $response->assertSessionHasErrors('password');
    }

    public function test_empty_payload_highlights_required_fields(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/teachers', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'status']);
    }

    #[DataProvider('invalidPayloads')]
    public function test_creation_rejects_invalid_input(string $field, mixed $value): void
    {
        $payload = $this->validPayload();
        $payload[$field] = $value;

        $response = $this->actingAs($this->admin())->post('/admin/teachers', $payload);

        $response->assertSessionHasErrors($field);
    }

    public static function invalidPayloads(): array
    {
        return [
            'invalid email format' => ['email', 'not-an-email'],
            'invalid status' => ['status', 'pending'],
        ];
    }

    public function test_valid_payload_updates_teacher(): void
    {
        $teacher = Teacher::factory()->create();
        $payload = $this->validPayload($teacher->email);
        $payload['first_name'] = 'Renamed';
        $payload['department'] = 'Science';

        $response = $this->actingAs($this->admin())->put("/admin/teachers/{$teacher->id}", $payload);

        $response->assertRedirect(route('teachers.show', $teacher));
        $this->assertDatabaseHas('teachers', ['id' => $teacher->id, 'first_name' => 'Renamed', 'department' => 'Science']);
    }

    public function test_deleting_teacher_removes_record_and_linked_login(): void
    {
        $teacher = Teacher::factory()->withUser()->create();

        $response = $this->actingAs($this->admin())->delete("/admin/teachers/{$teacher->id}");

        $response->assertRedirect(route('teachers.index'));
        $this->assertModelMissing($teacher);
        $this->assertDatabaseMissing('users', ['id' => $teacher->user_id]);
    }

    public function test_updating_teacher_resets_linked_login_password(): void
    {
        $teacher = Teacher::factory()->withUser()->create();
        $payload = $this->validPayload($teacher->email);
        $payload['reset_password'] = 'new-secret';

        $this->actingAs($this->admin())->put("/admin/teachers/{$teacher->id}", $payload);

        $this->assertTrue(password_verify('new-secret', $teacher->user->fresh()->password));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }

    private function validPayload(string $email = 'teacher@example.com'): array
    {
        return [
            'first_name' => 'Jane',
            'middle_name' => 'A',
            'last_name' => 'Doe',
            'email' => $email,
            'phone' => '+1 555 0100',
            'department' => 'Computer Studies',
            'status' => 'active',
        ];
    }
}
