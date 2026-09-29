<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /** Editable site settings with their Russian labels and defaults. */
    public const FIELDS = [
        'facebook_url' => ['Ссылка на Facebook', ''],
        'instagram_url' => ['Ссылка на Instagram', ''],
        'phone' => ['Телефон', '+374 00 000 000'],
        'email' => ['Email', 'info@rennova.am'],
        'address' => ['Адрес', 'Ереван, Армения'],
        'work_hours' => ['Часы работы', 'Пн–Сб, 10:00–19:00'],
        'whatsapp' => ['WhatsApp (номер с кодом страны)', ''],
        'telegram' => ['Telegram (имя пользователя без @)', ''],
        'viber' => ['Viber (номер с кодом страны)', ''],
        'map_lat' => ['Карта: широта', '40.1776'],
        'map_lng' => ['Карта: долгота', '44.5126'],
    ];

    protected static ?array $cache = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        if (static::$cache === null) {
            try {
                static::$cache = Schema::hasTable('settings')
                    ? static::query()->pluck('value', 'key')->all()
                    : [];
            } catch (Throwable) {
                static::$cache = [];
            }
        }

        $value = static::$cache[$key] ?? null;

        return filled($value) ? $value : ($default ?? self::FIELDS[$key][1] ?? null);
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null;
    }
}
