<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = Schedule::query()->get(['student_id', 'subject_id', 'teacher_id', 'semester', 'academic_year']);

        if ($schedules->isEmpty()) {
            return;
        }

        $validGrades = [1.0, 1.25, 1.5, 1.75, 2.0, 2.25, 2.5, 2.75, 3.0];

        Grade::factory()
            ->count($schedules->count())
            ->sequence(fn (Sequence $sequence): array => [
                'student_id' => $schedules[$sequence->index]->student_id,
                'subject_id' => $schedules[$sequence->index]->subject_id,
                'teacher_id' => $schedules[$sequence->index]->teacher_id,
                'semester' => $schedules[$sequence->index]->semester,
                'academic_year' => $schedules[$sequence->index]->academic_year,
                'grade' => $validGrades[$sequence->index % count($validGrades)],
            ])
            ->create();
    }
}
