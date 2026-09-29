<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    public const CATEGORIES = [
        'design' => 'Дизайн',
        'architecture' => 'Архитектура',
        'renovation' => 'Ремонт',
    ];

    protected $fillable = ['user_id', 'category', 'title', 'body', 'is_pinned', 'is_closed', 'last_activity_at'];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_closed' => 'boolean',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(DiscussionReply::class)->oldest();
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
