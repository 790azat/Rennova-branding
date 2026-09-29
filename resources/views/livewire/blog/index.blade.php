<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Блог') }}</div>
            <div class="eyebrow">{{ __('Блог') }}</div>
            <h1>{{ __('Идеи, тренды и советы по ремонту') }}</h1>
            <p class="lead">{{ __('Пишем о дизайне интерьера, архитектуре, материалах и о том, как спланировать ремонт без сюрпризов.') }}</p>
        </div>
        <x-landmark name="eiffel" stroke="0.8" />
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
                @forelse ($posts as $post)
                    @include('partials.post-card')
                @empty
                    <div class="empty" style="grid-column:1/-1">{{ __('Статей пока нет.') }}</div>
                @endforelse
            </div>
            <div class="pagination">{{ $posts->links('partials.pagination') }}</div>
        </div>
    </section>
</div>
