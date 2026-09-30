<div wire:poll.4s.visible>
    <div class="admin-top">
        <h1>{{ __('Онлайн-чат') }}</h1>
        <div class="filters" style="margin:0">
            @foreach (['open' => 'Открытые', 'closed' => 'Закрытые', '' => 'Все'] as $key => $label)
                <button class="chip {{ $status === $key ? 'active' : '' }}" wire:click="$set('status', '{{ $key }}')">{{ __($label) }}</button>
            @endforeach
        </div>
    </div>

    <div class="chat-admin">
        <aside class="chat-list">
            @forelse ($conversations as $c)
                <button type="button" class="chat-list-item {{ $current?->id === $c->id ? 'active' : '' }}" wire:click="select({{ $c->id }})" wire:key="cl-{{ $c->id }}">
                    <span class="chat-list-top">
                        <strong>{{ $c->displayName() }}</strong>
                        <span class="small muted">{{ $c->last_message_at?->timezone(config('rennova.booking.timezone'))->format('d.m H:i') }}</span>
                    </span>
                    <span class="chat-list-preview">
                        @if ($c->latestMessage?->sender === 'admin'){{ __('Вы:') }} @endif{{ \Illuminate\Support\Str::limit($c->latestMessage?->body, 70) }}
                    </span>
                    @if ($c->unread_admin)<span class="count">{{ $c->unread_admin }}</span>@endif
                </button>
            @empty
                <div class="empty">{{ __('Сообщений пока нет. Когда посетитель напишет в чат на сайте, диалог появится здесь.') }}</div>
            @endforelse
        </aside>

        <section class="chat-thread">
            @if ($current)
                <header class="chat-thread-head">
                    <div>
                        <strong>{{ $current->displayName() }}</strong>
                        <div class="small muted">
                            @if ($current->contact){{ $current->contact }} · @endif
                            @if ($current->user){{ $current->user->email }} · @endif
                            {{ strtoupper($current->locale) }}
                            @if ($current->page_url) · <a href="{{ $current->page_url }}" target="_blank" rel="noopener">{{ __('страница') }}</a>@endif
                        </div>
                    </div>
                    <div class="chat-thread-actions">
                        @if ($current->status === 'open')
                            <button class="btn btn--ghost btn--sm" wire:click="setStatus('closed')">{{ __('Закрыть диалог') }}</button>
                        @else
                            <button class="btn btn--ghost btn--sm" wire:click="setStatus('open')">{{ __('Открыть снова') }}</button>
                        @endif
                        <button class="btn btn--danger btn--sm" wire:click="delete" wire:confirm="{{ __('Удалить диалог со всеми сообщениями?') }}">{{ __('Удалить') }}</button>
                    </div>
                </header>
                <div class="chat-body chat-body--admin" x-init="const s = () => $el.scrollTop = $el.scrollHeight; s(); new MutationObserver(s).observe($el, { childList: true, subtree: true })">
                    @foreach ($messages as $m)
                        <div class="chat-msg chat-msg--{{ $m->sender === 'admin' ? 'visitor' : 'admin' }}" wire:key="am-{{ $m->id }}">
                            <span class="chat-text">{{ $m->body }}</span>
                            <time>@if ($m->sender === 'admin'){{ $m->author?->name }} · @endif{{ $m->created_at->timezone(config('rennova.booking.timezone'))->format('d.m H:i') }}</time>
                        </div>
                    @endforeach
                </div>
                <form class="chat-send" wire:key="reply-{{ $current->id }}" x-data="{ text: '', busy: false, submit() { if (this.busy || ! this.text.trim()) return; this.busy = true; $wire.send(this.text).then(ok => { if (ok) this.text = '' }).finally(() => this.busy = false) } }" @submit.prevent="submit()">
                    <textarea class="input" rows="2" x-model="text" wire:ignore maxlength="4000" placeholder="{{ __('Ответ посетителю…') }}" @keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); submit() }"></textarea>
                    <button class="btn" type="submit" :disabled="busy">{{ __('Отправить') }}</button>
                </form>
                @error('reply')<div class="error">{{ $message }}</div>@enderror
            @else
                <div class="empty">{{ __('Выберите диалог слева.') }}</div>
            @endif
        </section>
    </div>
</div>
