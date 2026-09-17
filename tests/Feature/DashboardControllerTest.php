<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_statistics_and_recent_students(): void
    {
        $course = Course::factory()->create();
        $activeStudent = Student::factory()->create(['course_id' => $course->id, 'status' => 'active']);
        Student::factory()->create(['course_id' => $course->id, 'status' => 'inactive']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Total Students');
        $response->assertSee('Active Students');
        $response->assertSee('Inactive Students');
        $response->assertSee('Total Courses');
        $response->assertSee('Recent Students');
        $response->assertSee($activeStudent->full_name);
    }
}
