@php use App\Models\Setting; @endphp
<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Контакты') }}</div>
            <div class="eyebrow">{{ __('Контакты') }}</div>
            <h1>{{ __('Приходите, звоните, пишите') }}</h1>
            <p class="lead">{{ __('Ответим в мессенджере, по телефону или в офисе. Для встречи удобнее записаться заранее.') }}</p>
        </div>
        <x-landmark name="cascade" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap contacts">
            <div class="contact-list">
                <div><span>{{ __('Телефон') }}</span><a href="tel:{{ preg_replace('/[^+\d]/', '', Setting::get('phone')) }}">{{ Setting::get('phone') }}</a></div>
                <div><span>Email</span><a href="mailto:{{ Setting::get('email') }}">{{ Setting::get('email') }}</a></div>
                <div><span>{{ __('Адрес') }}</span><strong>{{ __(Setting::get('address')) }}</strong></div>
                <div><span>{{ __('Часы работы') }}</span><strong>{{ __(Setting::get('work_hours')) }}</strong></div>
                @if ($messengers)
                    <div>
                        <span>{{ __('Мессенджеры') }}</span>
                        <div class="messenger-buttons">
                            @foreach ($messengers as $network => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn--ghost btn--sm messenger messenger--{{ $network }}">@include('partials.messenger-icon', ['network' => $network]) {{ ['whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'viber' => 'Viber'][$network] ?? ucfirst($network) }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="hero-actions">
                    <a href="{{ route('booking') }}" class="btn" wire:navigate>{{ __('Записаться на консультацию') }}</a>
                    <a href="{{ route('calculator') }}" class="btn btn--ghost" wire:navigate>{{ __('Рассчитать стоимость') }}</a>
                </div>
            </div>
            <div class="map">
                @if ($map)
                    <iframe src="{{ $map }}" title="{{ __('Карта') }}" loading="lazy" referrerpolicy="no-referrer"></iframe>
                    <a href="{{ \App\Support\Contact::mapLink() }}" target="_blank" rel="noopener" class="small link-arrow" style="margin-top:12px">{{ __('Открыть карту') }}</a>
                @endif
            </div>
        </div>
    </section>
</div>
