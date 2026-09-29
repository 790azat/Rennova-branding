<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProject extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'category', 'service_id', 'location', 'year', 'area', 'duration', 'summary',
        'description', 'cover_image', 'before_image', 'after_image', 'gallery', 'landmark',
        'is_featured', 'is_published', 'sort', 'translations',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)->orderBy('sort')->orderByDesc('id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function categoryLabel(): string
    {
        return __(Service::CATEGORIES[$this->category] ?? $this->category);
    }

    public function coverUrl(): ?string
    {
        return $this->cover_image ?: $this->after_image ?: $this->before_image;
    }

    public function hasBeforeAfter(): bool
    {
        return filled($this->before_image) && filled($this->after_image);
    }
}
