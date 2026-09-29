@component('layouts.app', ['title' => 'Страница не найдена'])
    <section class="page-hero" style="padding:140px 0">
        <div class="wrap">
            <div class="eyebrow">Ошибка 404</div>
            <h1>Страница не найдена</h1>
            <p class="lead">Возможно, её перенесли или она ещё строится.</p>
            <a href="{{ route('home') }}" class="btn btn--light" style="margin-top:24px">На главную</a>
        </div>
        <x-landmark name="louvre" stroke="0.8" />
    </section>
@endcomponent
