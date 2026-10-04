<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? __($title).' — ' : '' }}{{ __('Админпанель') }} Rennova</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=8">
    <script src="{{ asset('js/select.js') }}?v=1" defer data-navigate-once></script>
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
</head>
<body>
@php
    $newOrders = \App\Models\ServiceOrder::where('status', 'new')->count();
    $newImports = \App\Models\ImportRequest::where('status', 'new')->count();
    $newAppointments = \App\Models\Appointment::where('status', 'new')->count();
    $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
    $unreadChats = (int) \App\Models\ChatConversation::where('status', 'open')->sum('unread_admin');
    $items = [
        ['admin.dashboard', 'Обзор', null],
        ['admin.chats', 'Онлайн-чат', $unreadChats],
        ['admin.orders', 'Заявки на услуги', $newOrders],
        ['admin.appointments', 'Записи на консультацию', $newAppointments],
        ['admin.imports', 'Запросы на импорт', $newImports],
        ['admin.client-projects*', 'Проекты клиентов', null],
        ['admin.reviews', 'Отзывы', $pendingReviews],
        ['admin.discussions', 'Обсуждения', null],
        ['admin.services', 'Услуги', null],
        ['admin.portfolio', 'Портфолио', null],
        ['admin.brands', 'Бренды', null],
        ['admin.products', 'Товары брендов', null],
        ['admin.posts', 'Блог', null],
        ['admin.users', 'Пользователи', null],
        ['admin.settings', 'Настройки и соцсети', null],
    ];
@endphp
<div class="admin">
    <aside class="admin-side">
        @include('partials.brand')
        <nav>
            @foreach ($items as [$route, $label, $count])
                <a href="{{ route(rtrim($route, '*')) }}" class="{{ request()->routeIs($route) ? 'active' : '' }}" wire:navigate>
                    {{ __($label) }} @if($count)<span class="count">{{ $count }}</span>@endif
                </a>
            @endforeach
            <div class="sep">{{ __('Язык') }}</div>
            <div style="padding:0 14px">@include('partials.lang')</div>
            <div class="sep">{{ __('Сайт') }}</div>
            <a href="{{ route('home') }}">{{ __('← На сайт') }}</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <a href="#" onclick="this.closest('form').submit(); return false;">{{ __('Выйти') }}</a>
            </form>
        </nav>
    </aside>
    <main class="admin-main">
        {{ $slot }}
    </main>
</div>
@livewireScripts
<script>
    // Resize in the browser (max 1800px, JPEG) and upload; resolves to the stored image URLs.
    window.rnUpload = async function (files) {
        const urls = [];
        for (const file of files) {
            const bmp = await createImageBitmap(file);
            const k = Math.min(1, 1800 / Math.max(bmp.width, bmp.height));
            const c = document.createElement('canvas');
            c.width = Math.round(bmp.width * k); c.height = Math.round(bmp.height * k);
            c.getContext('2d').drawImage(bmp, 0, 0, c.width, c.height);
            const res = await fetch(@js(route('admin.media.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ image: c.toDataURL('image/jpeg', 0.84) }),
            });
            const json = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(json.message || @js(__('Не удалось загрузить файл.')));
            urls.push(json.url);
        }
        return urls;
    };
</script>
</body>
</html>
