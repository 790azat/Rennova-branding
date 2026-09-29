<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('services.index') }}" wire:navigate>{{ __('Услуги') }}</a> / {{ $service->tr('title') }}</div>
            <div class="eyebrow">{{ $service->categoryLabel() }}</div>
            <h1>{{ $service->tr('title') }}</h1>
            <p class="lead">{{ $service->tr('excerpt') }}</p>
            @if ($service->priceLabel())
                <p style="font-weight:600;font-size:1.2rem;margin-top:24px">{{ $service->priceLabel() }}</p>
            @endif
        </div>
        <x-landmark :name="$service->landmark ?? 'eiffel'" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="eyebrow">{{ __('Что входит') }}</div>
                <div style="white-space:pre-line;margin-bottom:32px">{{ $service->tr('description') }}</div>
                @if ($service->tr('features'))
                    <div class="meaning">
                        @foreach ($service->tr('features') as $i => $feature)
                            <div><strong>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</strong><span>{{ $feature }}</span></div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="panel">
                <h3>{{ __('Заказать услугу') }}</h3>
                <p class="muted small">{{ __('Менеджер перезвонит, уточнит детали и рассчитает стоимость.') }}</p>
                <livewire:service-request-form :service-id="$service->id" />
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section section--bone">
            <div class="wrap">
                <div class="section-head"><h2>{{ __('Может пригодиться') }}</h2></div>
                <div class="grid grid-3">
                    @foreach ($related as $item)
                        <a href="{{ route('services.show', $item) }}" class="service-card" wire:navigate>
                            <x-landmark :name="$item->landmark ?? 'eiffel'" />
                            <span class="tag">{{ $item->categoryLabel() }}</span>
                            <h3>{{ $item->tr('title') }}</h3>
                            <p class="muted small">{{ $item->tr('excerpt') }}</p>
                            <div class="price">{{ $item->priceLabel() }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
