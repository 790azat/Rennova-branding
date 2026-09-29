<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title.' — ' : '' }}Админпанель Rennova</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=1">
    <meta name="robots" content="noindex">
    @livewireStyles
</head>
<body>
@php
    $newOrders = \App\Models\ServiceOrder::where('status', 'new')->count();
    $newImports = \App\Models\ImportRequest::where('status', 'new')->count();
    $items = [
        ['admin.dashboard', 'Обзор', null],
        ['admin.orders', 'Заявки на услуги', $newOrders],
        ['admin.imports', 'Запросы на импорт', $newImports],
        ['admin.discussions', 'Обсуждения', null],
        ['admin.services', 'Услуги', null],
        ['admin.brands', 'Бренды', null],
        ['admin.users', 'Пользователи', null],
        ['admin.settings', 'Настройки и соцсети', null],
    ];
@endphp
<div class="admin">
    <aside class="admin-side">
        @include('partials.brand')
        <nav>
            @foreach ($items as [$route, $label, $count])
                <a href="{{ route($route) }}" class="{{ request()->routeIs($route) ? 'active' : '' }}" wire:navigate>
                    {{ $label }} @if($count)<span class="count">{{ $count }}</span>@endif
                </a>
            @endforeach
            <div class="sep">Сайт</div>
            <a href="{{ route('home') }}">← На сайт</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <a href="#" onclick="this.closest('form').submit(); return false;">Выйти</a>
            </form>
        </nav>
    </aside>
    <main class="admin-main">
        {{ $slot }}
    </main>
</div>
@livewireScripts
</body>
</html>
