<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientProject extends Model
{
    public const STATUSES = [
        'planning' => 'Подготовка',
        'active' => 'В работе',
        'paused' => 'Приостановлен',
        'done' => 'Завершён',
    ];

    /** Default stages created with a new project, by service category. */
    public const STAGE_TEMPLATES = [
        'renovation' => ['Замеры и смета', 'Демонтаж', 'Черновые работы', 'Инженерные системы', 'Чистовая отделка', 'Уборка и сдача'],
        'design' => ['Бриф и обмеры', 'Планировочное решение', 'Концепция и 3D-визуализация', 'Рабочие чертежи', 'Комплектация'],
        'architecture' => ['Техническое задание', 'Эскизный проект', 'Проектная документация', 'Согласования', 'Рабочая документация'],
        'cleaning' => ['Осмотр объекта', 'Уборка', 'Контроль качества'],
        'bundle' => ['Концепция и дизайн-проект', 'Смета и договор', 'Ремонтные работы', 'Комплектация мебелью', 'Финальный клининг', 'Сдача объекта'],
    ];

    protected $fillable = ['user_id', 'service_id', 'title', 'address', 'status', 'starts_on', 'ends_on', 'manager', 'note'];

    protected function casts(): array
    {
        return ['starts_on' => 'date', 'ends_on' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(ClientProjectStage::class)->orderBy('sort')->orderBy('id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ClientProjectUpdate::class)->latest();
    }

    public function statusLabel(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }

    /** Percentage of stages done; a stage in progress counts as half. */
    public function progress(): int
    {
        $stages = $this->relationLoaded('stages') ? $this->stages : $this->stages()->get();
        if ($stages->isEmpty()) {
            return $this->status === 'done' ? 100 : 0;
        }
        $score = $stages->sum(fn ($s) => match ($s->status) { 'done' => 1, 'in_progress' => .5, default => 0 });

        return (int) round($score / $stages->count() * 100);
    }

    public function currentStage(): ?ClientProjectStage
    {
        $stages = $this->relationLoaded('stages') ? $this->stages : $this->stages()->get();

        return $stages->firstWhere('status', 'in_progress') ?? $stages->firstWhere('status', 'pending');
    }
}
