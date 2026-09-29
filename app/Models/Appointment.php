<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    public const STATUSES = [
        'new' => 'Новая',
        'confirmed' => 'Подтверждена',
        'done' => 'Состоялась',
        'cancelled' => 'Отменена',
    ];

    public const FORMATS = [
        'office' => 'В офисе',
        'online' => 'Онлайн (видеозвонок)',
        'onsite' => 'Выезд на объект',
    ];

    protected $fillable = ['user_id', 'service_id', 'name', 'phone', 'email', 'date', 'time', 'format', 'comment', 'status'];

    protected function casts(): array
    {
        return ['date' => 'date'];
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

    public function formatLabel(): string
    {
        return __(self::FORMATS[$this->format] ?? $this->format);
    }

    /** Times already taken on a date (cancelled bookings free their slot). */
    public static function takenTimes(string $date): array
    {
        return static::query()->whereDate('date', $date)->where('status', '!=', 'cancelled')->pluck('time')->all();
    }
}
