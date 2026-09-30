@php $messengers = \App\Support\Contact::messengers(); $unread = $conversation?->unread_visitor ?? 0; @endphp
<div class="fab" x-data="{ menu: false }" @click.outside="menu = false" @keydown.escape="menu = false"
     @if ($open) wire:poll.4s @elseif ($conversation) wire:poll.30s.visible @endif>
    @if ($open)
        <section class="chat" role="dialog" aria-label="{{ __('Онлайн-чат') }}" @keydown.escape.stop="$wire.closeChat()">
            <header class="chat-head">
                <div>
                    <strong>{{ __('Онлайн-чат Rennova') }}</strong>
                    <span>{{ __('Обычно отвечаем в течение 15 минут') }} · {{ __(\App\Models\Setting::get('work_hours')) }}</span>
                </div>
                <button type="button" class="chat-close" wire:click="closeChat" aria-label="{{ __('Закрыть') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </header>
            <div class="chat-body" x-init="const s = () => $el.scrollTop = $el.scrollHeight; s(); new MutationObserver(s).observe($el, { childList: true, subtree: true })">
                <div class="chat-msg chat-msg--admin">{{ __('Здравствуйте! Задайте вопрос о ремонте, дизайне или импорте, и менеджер ответит прямо здесь.') }}</div>
                @foreach ($messages as $m)
                    <div class="chat-msg chat-msg--{{ $m->sender }}" wire:key="cm-{{ $m->id }}">
                        <span class="chat-text">{{ $m->body }}</span>
                        <time>{{ $m->created_at->timezone(config('rennova.booking.timezone'))->format('H:i') }}</time>
                    </div>
                @endforeach
            </div>
            <form class="chat-form" x-data="{ text: '', busy: false, submit() { if (this.busy || ! this.text.trim()) return; this.busy = true; $wire.send(this.text).then(ok => { if (ok) this.text = '' }).finally(() => this.busy = false) } }" @submit.prevent="submit()">
                @unless ($conversation)
                    <div class="chat-intro">
                        <input class="input" wire:model="name" placeholder="{{ __('Ваше имя') }}" aria-label="{{ __('Ваше имя') }}">
                        <input class="input" wire:model="contact" placeholder="{{ __('Телефон или email (необязательно)') }}" aria-label="{{ __('Телефон или email') }}">
                    </div>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                @endunless
                <div class="chat-send">
                    <textarea class="input" rows="1" x-model="text" wire:ignore placeholder="{{ __('Напишите сообщение…') }}" aria-label="{{ __('Сообщение') }}" maxlength="2000"
                              @keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); submit() }"></textarea>
                    <button class="btn btn--sm" type="submit" :disabled="busy" aria-label="{{ __('Отправить') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
                @error('body')<div class="error">{{ $message }}</div>@enderror
            </form>
        </section>
    @else
        <div class="fab-list" x-show="menu" x-cloak x-transition>
            <button type="button" class="fab-item fab-item--chat" wire:click="openChat" @click="menu = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 5h16v11H8l-4 4z"/></svg>
                <span>{{ __('Онлайн-чат') }}</span>
            </button>
            @foreach ($messengers as $network => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" class="fab-item fab-item--{{ $network }}">
                    @include('partials.messenger-icon', ['network' => $network]) <span>{{ ['whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'viber' => 'Viber'][$network] ?? ucfirst($network) }}</span>
                </a>
            @endforeach
        </div>
        <button class="fab-btn" @click="menu = !menu" :aria-expanded="menu" aria-label="{{ __('Написать нам') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 5h16v11H8l-4 4z"/></svg>
            @if ($unread)<span class="fab-badge">{{ $unread }}</span>@endif
        </button>
    @endif
</div>
