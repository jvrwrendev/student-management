<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->lastName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'department' => $this->faker->optional()->word(),
            'status' => 'active',
        ];
    }

    /**
     * Attach a linked login account to the teacher.
     */
    public function withUser(): static
    {
        return $this->afterCreating(function (Teacher $teacher): void {
            $user = User::factory()->create([
                'name' => $teacher->full_name,
                'email' => $teacher->email,
                'role' => Role::Teacher,
            ]);

            $teacher->user()->associate($user)->save();
        });
    }
}
