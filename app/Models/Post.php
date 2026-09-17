<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getExcerpt(int $length = 120): string
    {
        return Str::limit(strip_tags($this->content), $length);
    }
}
