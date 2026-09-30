<?php

namespace App\Livewire\Admin;

use App\Models\ChatConversation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Онлайн-чат')]
class Chats extends Component
{
    #[Url(as: 'c')]
    public ?int $selected = null;

    #[Url]
    public string $status = 'open';

    public string $reply = '';

    public function select(int $id): void
    {
        $this->selected = $id;
        $this->reply = '';
        $this->resetErrorBag();
    }

    public function send(string $text = ''): bool
    {
        $this->reply = $text;
        $this->validate(['reply' => 'required|string|max:4000'], [], ['reply' => __('ответ')]);
        ChatConversation::findOrFail($this->selected)->post('admin', trim($this->reply), auth()->id());
        $this->reply = '';

        return true;
    }

    public function setStatus(string $status): void
    {
        abort_unless(array_key_exists($status, ChatConversation::STATUSES), 422);
        ChatConversation::findOrFail($this->selected)->update(['status' => $status]);
    }

    public function delete(): void
    {
        ChatConversation::findOrFail($this->selected)->delete();
        $this->selected = null;
    }

    public function render()
    {
        $current = $this->selected ? ChatConversation::with('user')->find($this->selected) : null;
        if ($current?->unread_admin) {
            $current->forceFill(['unread_admin' => 0])->save();
        }

        return view('livewire.admin.chats', [
            'conversations' => ChatConversation::query()->with('latestMessage')
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->whereNotNull('last_message_at')
                ->latest('last_message_at')->take(100)->get(),
            'current' => $current,
            'messages' => $current ? $current->messages()->with('author')->reorder('id', 'desc')->take(200)->get()->reverse() : collect(),
        ]);
    }
}
