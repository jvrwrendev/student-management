<?php

namespace Database\Factories;

use App\Enums\SchoolDay;
use App\Enums\Semester;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => Teacher::factory(),
            'room' => $this->faker->optional()->bothify('Room ###'),
            'day' => SchoolDay::Monday->value,
            'start_time' => '08:00',
            'end_time' => '09:30',
            'semester' => Semester::First->value,
            'academic_year' => '2025-2026',
        ];
    }
}
