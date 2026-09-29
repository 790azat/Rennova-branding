<?php

namespace App\Livewire\Discussions;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Discussion $discussion;

    public string $body = '';

    public function reply(): void
    {
        abort_unless(auth()->check(), 403);

        if ($this->discussion->is_closed && ! auth()->user()->isAdmin()) {
            $this->addError('body', __('Обсуждение закрыто.'));

            return;
        }

        $this->validate(['body' => 'required|string|min:2|max:5000'], [], ['body' => __('ответ')]);

        $this->discussion->replies()->create(['user_id' => auth()->id(), 'body' => $this->body]);
        $this->discussion->update(['last_activity_at' => now()]);
        $this->reset('body');
    }

    public function deleteReply(int $id): void
    {
        $reply = DiscussionReply::query()->where('discussion_id', $this->discussion->id)->findOrFail($id);
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || $reply->user_id === auth()->id()), 403);
        $reply->delete();
    }

    public function render()
    {
        return view('livewire.discussions.show', [
            'replies' => $this->discussion->replies()->with('user')->get(),
        ])->title($this->discussion->title);
    }
}
