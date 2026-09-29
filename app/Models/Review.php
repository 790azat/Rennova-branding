<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    public const STATUSES = [
        'pending' => 'На модерации',
        'approved' => 'Опубликован',
        'rejected' => 'Отклонён',
    ];

    protected $fillable = ['user_id', 'service_id', 'name', 'rating', 'body', 'reply', 'status'];

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', 'approved')->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLabel(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }
}
