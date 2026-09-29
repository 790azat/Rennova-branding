<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    public const STATUSES = [
        'new' => 'Новая',
        'in_progress' => 'В работе',
        'done' => 'Выполнена',
        'cancelled' => 'Отменена',
    ];

    protected $fillable = ['user_id', 'service_id', 'name', 'phone', 'email', 'message', 'status', 'source', 'estimate', 'details'];

    protected function casts(): array
    {
        return ['details' => 'array'];
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
