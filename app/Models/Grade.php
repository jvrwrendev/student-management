<?php

namespace App\Models;

use Database\Factories\GradeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    /** @use HasFactory<GradeFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'semester',
        'academic_year',
        'grade',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'grade' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function isPassed(): bool
    {
        return $this->grade <= 3.00;
    }

    /**
     * Percentage equivalent of the numeric grade on a 60-100 linear scale.
     */
    public function percentage(): float
    {
        return round(60 + (5 - (float) $this->grade) * 10, 2);
    }

    protected function result(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->isPassed() ? 'Passed' : 'Failed',
        );
    }
}
