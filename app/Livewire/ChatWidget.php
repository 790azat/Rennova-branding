<?php

namespace App\Livewire;

use App\Models\ChatConversation;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

/** Floating contact button: live chat with the Rennova team plus messenger links. */
class ChatWidget extends Component
{
    public bool $open = false;

    public string $name = '';

    public string $contact = '';

    /** Last message the visitor tried to send (the textarea itself is client-side so polling never wipes it). */
    public string $body = '';

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->name = $user->name;
            $this->contact = (string) ($user->phone ?: $user->email);
        }
    }

    public function conversation(): ?ChatConversation
    {
        $token = session('chat_token');
        $query = ChatConversation::query();

        if ($token) {
            return $query->where('token', $token)->first();
        }

        return auth()->check() ? $query->where('user_id', auth()->id())->latest('last_message_at')->first() : null;
    }

    public function openChat(): void
    {
        $this->open = true;
    }

    public function closeChat(): void
    {
        $this->open = false;
    }

    public function send(string $text = ''): bool
    {
        $this->body = $text;
        $conversation = $this->conversation();

        $this->validate([
            'body' => 'required|string|max:2000',
            'name' => $conversation ? 'nullable' : 'required|string|min:2|max:120',
            'contact' => 'nullable|string|max:160',
        ], [], ['body' => __('сообщение'), 'name' => __('имя')]);

        $key = 'chat:'.(session()->getId() ?: request()->ip());
        if (RateLimiter::tooManyAttempts($key, 8)) {
            $this->addError('body', __('Слишком много сообщений. Подождите минуту.'));

            return false;
        }
        RateLimiter::hit($key, 60);

        if (! $conversation) {
            $conversation = ChatConversation::create([
                'token' => Str::random(40),
                'user_id' => auth()->id(),
                'name' => trim($this->name),
                'contact' => trim($this->contact) ?: null,
                'locale' => app()->getLocale(),
                'page_url' => mb_substr((string) request()->header('Referer', url()->previous()), 0, 500),
            ]);
        }
        session(['chat_token' => $conversation->token]);

        $conversation->post('visitor', trim($this->body), auth()->id());
        $this->body = '';

        return true;
    }

    public function render()
    {
        $conversation = $this->conversation();

        if ($this->open && $conversation?->unread_visitor) {
            $conversation->forceFill(['unread_visitor' => 0])->save();
        }

        return view('livewire.chat-widget', [
            'conversation' => $conversation,
            'messages' => $this->open && $conversation
                ? $conversation->messages()->reorder('id', 'desc')->take(60)->get()->reverse()
                : collect(),
        ]);
    }
}
