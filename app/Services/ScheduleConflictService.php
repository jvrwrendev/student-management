<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

class ScheduleConflictService
{
    /**
     * Find schedules that overlap the given time window for the same student,
     * teacher, day, and term.
     *
     * @param  array<string, int|string>  $attributes
     * @return Collection<int, Schedule>
     */
    public function conflicts(array $attributes, int $ignoreScheduleId = 0): Collection
    {
        return Schedule::query()
            ->where('day', $attributes['day'])
            ->where('semester', $attributes['semester'])
            ->where('academic_year', $attributes['academic_year'])
            ->where('start_time', '<', $attributes['end_time'])
            ->where('end_time', '>', $attributes['start_time'])
            ->when($ignoreScheduleId > 0, fn ($query) => $query->where('id', '!=', $ignoreScheduleId))
            ->where(function ($query) use ($attributes): void {
                $query->where('student_id', $attributes['student_id'])
                    ->orWhere('teacher_id', $attributes['teacher_id']);
            })
            ->with(['student', 'teacher', 'subject'])
            ->get();
    }
}
