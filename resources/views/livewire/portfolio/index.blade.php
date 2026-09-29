<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Портфолио') }}</div>
            <div class="eyebrow">{{ __('Портфолио') }}</div>
            <h1>{{ __('Проекты, которыми мы гордимся') }}</h1>
            <p class="lead">{{ __('Реальные объекты Rennova: ремонт, дизайн, архитектура и клининг. Сравните, как было и как стало.') }}</p>
        </div>
        <x-landmark name="empire" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            @if (count($categories) > 1)
                <div class="filters">
                    <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">{{ __('Все') }}</button>
                    @foreach ($categories as $key => $label)
                        <button class="chip {{ $category === $key ? 'active' : '' }}" wire:click="$set('category', @js($key))">{{ __($label) }}</button>
                    @endforeach
                </div>
            @endif
            <div class="grid grid-3">
                @forelse ($projects as $project)
                    @include('partials.project-card')
                @empty
                    <div class="empty" style="grid-column:1/-1">{{ __('Скоро здесь появятся наши проекты. А пока оцените стоимость своего.') }}
                        <div style="margin-top:20px"><a href="{{ route('calculator') }}" class="btn" wire:navigate>{{ __('Рассчитать стоимость') }}</a></div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
