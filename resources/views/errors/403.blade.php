@component('layouts.app', ['title' => 'Доступ закрыт'])
    <section class="page-hero" style="padding:140px 0">
        <div class="wrap">
            <div class="eyebrow">Ошибка 403</div>
            <h1>Доступ закрыт</h1>
            <p class="lead">У вас нет прав для просмотра этой страницы.</p>
            <a href="{{ route('home') }}" class="btn btn--light" style="margin-top:24px">На главную</a>
        </div>
        <x-landmark name="bigben" stroke="0.8" />
    </section>
@endcomponent
