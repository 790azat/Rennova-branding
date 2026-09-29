<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportRequest extends Model
{
    public const PRODUCT_TYPES = [
        'finishing' => 'Отделочные материалы',
        'furniture' => 'Мебель',
        'lighting' => 'Освещение',
        'plumbing' => 'Сантехника',
        'stone' => 'Натуральный камень',
        'textile' => 'Текстиль и декор',
        'smart_home' => 'Умный дом и техника',
        'other' => 'Другое',
    ];

    public const STATUSES = [
        'new' => 'Новая',
        'reviewing' => 'На рассмотрении',
        'quoted' => 'Расчёт отправлен',
        'ordered' => 'Заказано',
        'delivered' => 'Доставлено',
        'rejected' => 'Отклонена',
    ];

    protected $fillable = [
        'user_id', 'product_type', 'title', 'description', 'preferred_brand',
        'quantity', 'budget', 'status', 'admin_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return __(self::PRODUCT_TYPES[$this->product_type] ?? $this->product_type);
    }

    public function statusLabel(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }
}
