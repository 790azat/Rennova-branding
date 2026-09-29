<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProjectStage extends Model
{
    public const STATUSES = [
        'pending' => 'Впереди',
        'in_progress' => 'Идёт сейчас',
        'done' => 'Готово',
    ];

    protected $fillable = ['client_project_id', 'title', 'status', 'ends_on', 'note', 'sort'];

    protected function casts(): array
    {
        return ['ends_on' => 'date'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }

    public function statusLabel(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }
}
