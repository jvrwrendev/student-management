<?php

namespace Database\Seeders;

use App\Enums\SchoolDay;
use App\Enums\Semester;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentIds = Student::query()->pluck('id')->all();
        $subjectIds = Subject::query()->pluck('id')->all();
        $teacherIds = Teacher::query()->pluck('id')->all();

        if (empty($studentIds) || empty($subjectIds) || empty($teacherIds)) {
            return;
        }

        $slots = [
            ['start_time' => '08:00', 'end_time' => '09:30'],
            ['start_time' => '10:00', 'end_time' => '11:30'],
            ['start_time' => '13:00', 'end_time' => '14:30'],
            ['start_time' => '15:00', 'end_time' => '16:30'],
        ];

        $attributes = [];

        foreach ($studentIds as $studentIndex => $studentId) {
            foreach ($slots as $slotIndex => $slot) {
                $offset = $studentIndex * count($slots) + $slotIndex;

                $attributes[] = [
                    'student_id' => $studentId,
                    'subject_id' => $subjectIds[$offset % count($subjectIds)],
                    'teacher_id' => $teacherIds[$offset % count($teacherIds)],
                    'room' => 'Room '.str_pad((string) (($offset % 20) + 101), 3, '0', STR_PAD_LEFT),
                    'day' => SchoolDay::options()[$offset % 6] ?? SchoolDay::Monday->value,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'semester' => Semester::First->value,
                    'academic_year' => '2026-2027',
                ];
            }
        }

        Schedule::factory()
            ->count(count($attributes))
            ->sequence(fn (Sequence $sequence): array => $attributes[$sequence->index])
            ->create();
    }
}
