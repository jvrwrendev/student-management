<?php

namespace App\Enums;

enum Semester: string
{
    case First = '1st Semester';
    case Second = '2nd Semester';
    case Summer = 'Summer';

    /**
     * @return array<int, string>
     */
    public static function options(): array
    {
        return array_map(fn (self $semester): string => $semester->value, self::cases());
    }
}
