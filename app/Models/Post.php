<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasTranslations;

    public const CATEGORIES = [
        'trends' => 'Тренды',
        'renovation' => 'Ремонт',
        'design' => 'Дизайн',
        'architecture' => 'Архитектура',
        'cleaning' => 'Клининг',
        'materials' => 'Материалы',
    ];

    protected $fillable = [
        'slug', 'title', 'category', 'excerpt', 'body', 'cover_image', 'landmark', 'author_id', 'published_at', 'translations',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now())->orderByDesc('published_at');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categoryLabel(): ?string
    {
        return $this->category ? __(self::CATEGORIES[$this->category] ?? $this->category) : null;
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }

    /** Body is Markdown; raw HTML in it is stripped. */
    public function html(): HtmlString
    {
        return new HtmlString(Str::markdown((string) $this->tr('body'), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }

    public function readingMinutes(): int
    {
        $words = preg_split('/\s+/u', trim(strip_tags((string) $this->tr('body'))), -1, PREG_SPLIT_NO_EMPTY);

        return max(1, (int) round(count($words) / 180));
    }
}
