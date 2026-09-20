<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_subjects_page_lists_subjects(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->actingAs($this->admin())->get('/admin/subjects');

        $response->assertOk();
        $response->assertSee($subject->subject_name);
        $response->assertSee($subject->subject_code);
    }

    public function test_valid_payload_creates_subject_and_redirects_to_index(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/subjects', $this->validPayload());

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('subjects', ['subject_code' => 'IT-999']);
    }

    public function test_valid_payload_updates_subject(): void
    {
        $subject = Subject::factory()->create();
        $payload = $this->validPayload($subject->subject_code);
        $payload['subject_name'] = 'Updated Subject';

        $response = $this->actingAs($this->admin())->put("/admin/subjects/{$subject->id}", $payload);

        $response->assertRedirect(route('subjects.index'));
        $this->assertDatabaseHas('subjects', ['id' => $subject->id, 'subject_name' => 'Updated Subject']);
    }

    public function test_deleting_subject_removes_record(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->actingAs($this->admin())->delete("/admin/subjects/{$subject->id}");

        $response->assertRedirect(route('subjects.index'));
        $this->assertModelMissing($subject);
    }

    public function test_empty_payload_highlights_required_fields(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/subjects', []);

        $response->assertSessionHasErrors(['subject_code', 'subject_name', 'status']);
    }

    #[DataProvider('invalidPayloads')]
    public function test_creation_rejects_invalid_input(string $field, mixed $value): void
    {
        $payload = $this->validPayload();
        $payload[$field] = $value;

        $response = $this->actingAs($this->admin())->post('/admin/subjects', $payload);

        $response->assertSessionHasErrors($field);
    }

    public static function invalidPayloads(): array
    {
        return [
            'invalid status' => ['status', 'archived'],
        ];
    }

    public function test_creation_rejects_duplicate_subject_code(): void
    {
        Subject::factory()->create(['subject_code' => 'IT-999']);

        $response = $this->actingAs($this->admin())->post('/admin/subjects', $this->validPayload());

        $response->assertSessionHasErrors('subject_code');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => Role::Admin]);
    }

    private function validPayload(?string $code = null): array
    {
        return [
            'subject_code' => $code ?? 'IT-999',
            'subject_name' => 'Web Applications',
            'description' => 'Building web applications with modern frameworks.',
            'status' => 'active',
        ];
    }
}
