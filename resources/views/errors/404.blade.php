@component('layouts.app', ['title' => __('Страница не найдена')])
    <section class="page-hero" style="padding:140px 0">
        <div class="wrap">
            <div class="eyebrow">{{ __('Ошибка 404') }}</div>
            <h1>{{ __('Страница не найдена') }}</h1>
            <p class="lead">{{ __('Возможно, её перенесли или она ещё строится.') }}</p>
            <a href="{{ route('home') }}" class="btn btn--light" style="margin-top:24px">{{ __('На главную') }}</a>
        </div>
        <x-landmark name="louvre" stroke="0.8" />
    </section>
@endcomponent
