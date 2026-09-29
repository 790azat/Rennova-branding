@component('layouts.app', ['title' => __('Доступ закрыт')])
    <section class="page-hero" style="padding:140px 0">
        <div class="wrap">
            <div class="eyebrow">{{ __('Ошибка 403') }}</div>
            <h1>{{ __('Доступ закрыт') }}</h1>
            <p class="lead">{{ __('У вас нет прав для просмотра этой страницы.') }}</p>
            <a href="{{ route('home') }}" class="btn btn--light" style="margin-top:24px">{{ __('На главную') }}</a>
        </div>
        <x-landmark name="bigben" stroke="0.8" />
    </section>
@endcomponent
