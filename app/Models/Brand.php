<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'slug', 'name', 'country', 'category', 'tagline', 'description', 'website',
        'logo_url', 'is_exclusive', 'is_featured', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'is_exclusive' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('name');
    }

    public function monogram(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
