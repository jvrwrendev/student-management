<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseIds = Course::query()->pluck('id')->all();

        Student::factory()
            ->count(25)
            ->sequence(fn (Sequence $sequence): array => [
                'course_id' => $courseIds[$sequence->index % count($courseIds)],
            ])
            ->create();
    }
}
