<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    public const STATUSES = ['open' => 'Открыт', 'closed' => 'Закрыт'];

    protected $fillable = ['token', 'user_id', 'name', 'contact', 'locale', 'page_url', 'status', 'unread_admin', 'unread_visitor', 'last_message_at'];

    protected function casts(): array
    {
        return ['last_message_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function displayName(): string
    {
        return $this->name ?: ($this->user?->name ?? __('Гость #:id', ['id' => $this->id]));
    }

    /** Adds a message and updates unread counters and ordering. */
    public function post(string $sender, string $body, ?int $userId = null): ChatMessage
    {
        $message = $this->messages()->create(['sender' => $sender, 'body' => $body, 'user_id' => $userId]);
        $this->forceFill([
            'last_message_at' => $message->created_at,
            'status' => 'open',
            $sender === 'visitor' ? 'unread_admin' : 'unread_visitor' => ($sender === 'visitor' ? $this->unread_admin : $this->unread_visitor) + 1,
        ])->save();

        return $message;
    }
}
