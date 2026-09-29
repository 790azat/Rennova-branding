<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasTranslations;

    public const CATEGORIES = [
        'cleaning' => 'Клининг',
        'renovation' => 'Ремонт',
        'design' => 'Дизайн интерьера',
        'architecture' => 'Архитектура',
        'bundle' => 'Комплексная услуга',
    ];

    protected $fillable = [
        'slug', 'title', 'category', 'excerpt', 'description', 'features',
        'price_from', 'price_unit', 'landmark', 'is_bundle', 'is_active', 'sort', 'translations',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_bundle' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function categoryLabel(): string
    {
        return __(self::CATEGORIES[$this->category] ?? $this->category);
    }

    public function priceLabel(): ?string
    {
        if (! $this->price_from) {
            return null;
        }

        return __('от :price', ['price' => number_format($this->price_from, 0, ',', ' ').' ֏'])
            .($this->price_unit ? ' / '.__($this->price_unit) : '');
    }
}
