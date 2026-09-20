<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => 'STU-'.$this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->lastName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-25 years', '-17 years')->format('Y-m-d'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'course_id' => Course::factory(),
            'year_level' => $this->faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Attach a linked student login account to the student.
     */
    public function withUser(): static
    {
        return $this->afterCreating(function (Student $student): void {
            $user = User::factory()->create([
                'name' => $student->full_name,
                'email' => $student->email,
                'role' => Role::Student,
            ]);

            $student->update(['user_id' => $user->id]);
        });
    }
}
