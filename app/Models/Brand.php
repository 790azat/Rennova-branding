<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'name', 'country', 'category', 'tagline', 'description', 'website',
        'logo_url', 'is_exclusive', 'is_featured', 'is_active', 'sort', 'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_exclusive' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('name');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function monogram(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
