@php $messengers = \App\Support\Contact::messengers(); @endphp
@if ($messengers)
    <div class="fab" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
        <div class="fab-list" x-show="open" x-cloak x-transition>
            @foreach ($messengers as $network => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" class="fab-item fab-item--{{ $network }}">
                    @include('partials.messenger-icon', ['network' => $network]) <span>{{ ['whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'viber' => 'Viber'][$network] ?? ucfirst($network) }}</span>
                </a>
            @endforeach
        </div>
        <button class="fab-btn" @click="open = !open" :aria-expanded="open" aria-label="{{ __('Написать нам') }}">
            <svg x-show="!open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 5h16v11H8l-4 4z"/></svg>
            <svg x-show="open" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>
@endif
