<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title.' — Rennova' : 'Rennova — ремонт, дизайн, архитектура и клининг' }}</title>
    <meta name="description" content="Rennova by Metruminvest: ремонт под ключ, дизайн интерьера, архитектура и клининг в Армении. Эксклюзивные бренды и импорт материалов.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=1">
    @livewireStyles
</head>
<body>
<header class="site-header" x-data="{ open: false }">
    <div class="wrap">
        @include('partials.brand')
        <nav class="nav" :class="{ open }">
            <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}" wire:navigate>Услуги</a>
            <a href="{{ route('brands') }}" class="{{ request()->routeIs('brands') ? 'active' : '' }}" wire:navigate>Бренды</a>
            <a href="{{ route('discussions.index') }}" class="{{ request()->routeIs('discussions.*') ? 'active' : '' }}" wire:navigate>Обсуждения</a>
            <a href="{{ route('imports') }}" class="{{ request()->routeIs('imports') ? 'active' : '' }}" wire:navigate>Импорт товаров</a>
            <a href="{{ route('home') }}#contacts">Контакты</a>
            @guest<a href="{{ route('login') }}" class="nav-login" wire:navigate>Войти</a>@endguest
        </nav>
        <div class="header-actions">
            @include('partials.socials')
            @auth
                <div class="user-menu" x-data="{ menu: false }" @click.outside="menu = false">
                    <button class="avatar" @click="menu = !menu" aria-label="Меню пользователя">{{ auth()->user()->initials() }}</button>
                    <div class="dropdown" x-show="menu" x-cloak x-transition>
                        <div class="who">{{ auth()->user()->name }}<br>{{ auth()->user()->email }}</div>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Админпанель</a>
                        @endif
                        <a href="{{ route('profile') }}" wire:navigate>Профиль и заявки</a>
                        <a href="{{ route('imports') }}" wire:navigate>Мои запросы на импорт</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Выйти</button></form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn--ghost btn--sm" wire:navigate>Войти</a>
            @endauth
            <button class="burger" @click="open = !open" aria-label="Меню">
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
                <p class="small" style="margin-top:24px;max-width:320px">Ремонт под ключ, дизайн, архитектура и клининг. От концепции до финальной сдачи объекта.</p>
                @include('partials.socials')
            </div>
            <div>
                <h4>Услуги</h4>
                <ul>
                    @foreach (\App\Models\Service::CATEGORIES as $key => $label)
                        <li><a href="{{ route('services.index', ['category' => $key]) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4>Сообщество</h4>
                <ul>
                    <li><a href="{{ route('brands') }}">Эксклюзивные бренды</a></li>
                    <li><a href="{{ route('discussions.index') }}">Обсуждения проектов</a></li>
                    <li><a href="{{ route('imports') }}">Импорт товаров</a></li>
                    @guest<li><a href="{{ route('register') }}">Регистрация</a></li>@endguest
                </ul>
            </div>
            <div>
                <h4>Контакты</h4>
                <ul>
                    <li><a href="tel:{{ preg_replace('/[^+\d]/', '', \App\Models\Setting::get('phone')) }}">{{ \App\Models\Setting::get('phone') }}</a></li>
                    <li><a href="mailto:{{ \App\Models\Setting::get('email') }}">{{ \App\Models\Setting::get('email') }}</a></li>
                    <li>{{ \App\Models\Setting::get('address') }}</li>
                </ul>
            </div>
        </div>
        <div class="skyline">
            @foreach (array_keys(config('rennova.landmarks')) as $landmark)
                <x-landmark :name="$landmark" />
            @endforeach
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Rennova by Metruminvest</span>
            <span>Вдохновлено архитектурой мира</span>
        </div>
    </div>
</footer>

@if (session('status'))
    <div class="toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>{{ session('status') }}</div>
@endif
@livewireScripts
</body>
</html>
