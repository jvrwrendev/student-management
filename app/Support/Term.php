<?php

namespace App\Support;

use App\Enums\Semester;
use App\Models\Grade;
use App\Models\Schedule;

class Term
{
    public static function currentAcademicYear(): string
    {
        $year = now()->year;

        return "{$year}-".($year + 1);
    }

    /**
     * Distinct academic years derived from existing records, newest first.
     *
     * @return array<int, string>
     */
    public static function academicYears(): array
    {
        $years = Grade::query()->pluck('academic_year')
            ->merge(Schedule::query()->pluck('academic_year'))
            ->map(fn (string $year): string => trim($year))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->push(self::currentAcademicYear())
            ->unique()
            ->values()
            ->all();

        return $years;
    }

    /**
     * @return array<int, string>
     */
    public static function semesters(): array
    {
        return Semester::options();
    }
}
