<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? __($title).' — Rennova' : 'Rennova — '.__('ремонт, дизайн, архитектура и клининг') }}</title>
    <meta name="description" content="{{ $description ?? __('Rennova by Metruminvest: ремонт под ключ, дизайн интерьера, архитектура и клининг в Армении. Эксклюзивные бренды и импорт материалов.') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=7">
    @stack('head')
    @livewireStyles
</head>
<body>
<header class="site-header" x-data="{ open: false }">
    <div class="wrap">
        @include('partials.brand')
        <nav class="nav" :class="{ open }">
            <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}" wire:navigate>{{ __('Услуги') }}</a>
            <a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio*') ? 'active' : '' }}" wire:navigate>{{ __('Портфолио') }}</a>
            <a href="{{ route('brands') }}" class="{{ request()->routeIs('brands*') ? 'active' : '' }}" wire:navigate>{{ __('Бренды') }}</a>
            <a href="{{ route('calculator') }}" class="{{ request()->routeIs('calculator') ? 'active' : '' }}" wire:navigate>{{ __('Калькулятор') }}</a>
            <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}" wire:navigate>{{ __('Блог') }}</a>
            <div class="nav-more" x-data="{ more: false }" @click.outside="more = false" @mouseleave="more = false">
                <button type="button" class="{{ request()->routeIs('discussions.*', 'imports', 'reviews') ? 'active' : '' }}" @click="more = !more" @mouseenter="more = true">{{ __('Сообщество') }} <span aria-hidden="true">▾</span></button>
                <div class="nav-more-list" :class="{ show: more }">
                    <a href="{{ route('discussions.index') }}" wire:navigate>{{ __('Обсуждения') }}</a>
                    <a href="{{ route('imports') }}" wire:navigate>{{ __('Импорт товаров') }}</a>
                    <a href="{{ route('reviews') }}" wire:navigate>{{ __('Отзывы') }}</a>
                </div>
            </div>
            <a href="{{ route('contacts') }}" class="{{ request()->routeIs('contacts') ? 'active' : '' }}" wire:navigate>{{ __('Контакты') }}</a>
            <a href="{{ route('booking') }}" class="nav-login" wire:navigate>{{ __('Консультация') }}</a>
            @guest<a href="{{ route('login') }}" class="nav-login" wire:navigate>{{ __('Войти') }}</a>@endguest
        </nav>
        <div class="header-actions">
            @include('partials.lang')
            @include('partials.socials')
            <a href="tel:{{ preg_replace('/[^+\d]/', '', \App\Models\Setting::get('phone')) }}" class="header-phone">{{ \App\Models\Setting::get('phone') }}</a>
            <a href="{{ route('booking') }}" class="btn btn--sm header-cta" wire:navigate>{{ __('Консультация') }}</a>
            @auth
                <div class="user-menu" x-data="{ menu: false }" @click.outside="menu = false">
                    <button class="avatar" @click="menu = !menu" aria-label="{{ __('Меню пользователя') }}">{{ auth()->user()->initials() }}</button>
                    <div class="dropdown" x-show="menu" x-cloak x-transition>
                        <div class="who">{{ auth()->user()->name }}<br>{{ auth()->user()->email }}</div>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">{{ __('Админпанель') }}</a>
                        @endif
                        <a href="{{ route('cabinet') }}" wire:navigate>{{ __('Мои проекты') }}</a>
                        <a href="{{ route('profile') }}" wire:navigate>{{ __('Профиль и заявки') }}</a>
                        <a href="{{ route('imports') }}" wire:navigate>{{ __('Мои запросы на импорт') }}</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">{{ __('Выйти') }}</button></form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn--ghost btn--sm" wire:navigate>{{ __('Войти') }}</a>
            @endauth
            <button class="burger" @click="open = !open" aria-label="{{ __('Меню') }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
            </button>
        </div>
    </div>
</header>

<main>
    {{ $slot }}
</main>

<footer class="site-footer" id="contacts">
    <div class="wrap">
        <div class="footer-grid">
            <div>
                @include('partials.brand')
                <p class="small" style="margin-top:24px;max-width:320px">{{ __('Ремонт под ключ, дизайн, архитектура и клининг. От концепции до финальной сдачи объекта.') }}</p>
                @include('partials.socials')
            </div>
            <div>
                <h4>{{ __('Услуги') }}</h4>
                <ul>
                    @foreach (\App\Models\Service::CATEGORIES as $key => $label)
                        <li><a href="{{ route('services.index', ['category' => $key]) }}">{{ __($label) }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4>{{ __('Сообщество') }}</h4>
                <ul>
                    <li><a href="{{ route('portfolio') }}">{{ __('Портфолио') }}</a></li>
                    <li><a href="{{ route('brands') }}">{{ __('Эксклюзивные бренды') }}</a></li>
                    <li><a href="{{ route('calculator') }}">{{ __('Калькулятор стоимости') }}</a></li>
                    <li><a href="{{ route('blog') }}">{{ __('Блог') }}</a></li>
                    <li><a href="{{ route('reviews') }}">{{ __('Отзывы') }}</a></li>
                    <li><a href="{{ route('discussions.index') }}">{{ __('Обсуждения проектов') }}</a></li>
                    <li><a href="{{ route('imports') }}">{{ __('Импорт товаров') }}</a></li>
                    @guest<li><a href="{{ route('register') }}">{{ __('Регистрация') }}</a></li>@endguest
                </ul>
            </div>
            <div>
                <h4>{{ __('Контакты') }}</h4>
                <ul>
                    <li><a href="tel:{{ preg_replace('/[^+\d]/', '', \App\Models\Setting::get('phone')) }}">{{ \App\Models\Setting::get('phone') }}</a></li>
                    <li><a href="mailto:{{ \App\Models\Setting::get('email') }}">{{ \App\Models\Setting::get('email') }}</a></li>
                    <li>{{ __(\App\Models\Setting::get('address')) }}</li>
                    <li>{{ __(\App\Models\Setting::get('work_hours')) }}</li>
                    <li><a href="{{ route('contacts') }}">{{ __('Карта и мессенджеры') }}</a></li>
                    <li><a href="{{ route('booking') }}">{{ __('Записаться на консультацию') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Rennova by Metruminvest</span>
            <span>{{ __('Вдохновлено архитектурой мира') }}</span>
        </div>
    </div>
</footer>

@persist('chat')
    <livewire:chat-widget />
@endpersist

<div class="mobile-bar">
    <a href="tel:{{ preg_replace('/[^+\d]/', '', \App\Models\Setting::get('phone')) }}" class="btn btn--ghost btn--sm">{{ __('Позвонить') }}</a>
    <a href="{{ route('calculator') }}" class="btn btn--sm" wire:navigate>{{ __('Рассчитать цену') }}</a>
</div>

@if (session('status'))
    <div class="toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>{{ session('status') }}</div>
@endif
@livewireScripts
</body>
</html>
