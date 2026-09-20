<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_code' => 'SUB-'.$this->faker->unique()->numberBetween(1000, 9999),
            'subject_name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'units' => $this->faker->randomElement([3.0, 3.0, 3.0, 4.0, 2.0]),
            'status' => 'active',
        ];
    }
}
