<?php

namespace App\Enums;

enum SchoolDay: string
{
    case Monday = 'Monday';
    case Tuesday = 'Tuesday';
    case Wednesday = 'Wednesday';
    case Thursday = 'Thursday';
    case Friday = 'Friday';
    case Saturday = 'Saturday';

    /**
     * @return array<int, string>
     */
    public static function options(): array
    {
        return array_map(fn (self $day): string => $day->value, self::cases());
    }
}
