<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>Главная</a> / <a href="{{ route('services.index') }}" wire:navigate>Услуги</a> / {{ $service->title }}</div>
            <div class="eyebrow">{{ $service->categoryLabel() }}</div>
            <h1>{{ $service->title }}</h1>
            <p class="lead">{{ $service->excerpt }}</p>
            @if ($service->priceLabel())
                <p style="font-weight:600;font-size:1.2rem;margin-top:24px">{{ $service->priceLabel() }}</p>
            @endif
        </div>
        <x-landmark :name="$service->landmark ?? 'eiffel'" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="eyebrow">Что входит</div>
                <div style="white-space:pre-line;margin-bottom:32px">{{ $service->description }}</div>
                @if ($service->features)
                    <div class="meaning">
                        @foreach ($service->features as $i => $feature)
                            <div><strong>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</strong><span>{{ $feature }}</span></div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="panel">
                <h3>Заказать услугу</h3>
                <p class="muted small">Менеджер перезвонит, уточнит детали и рассчитает стоимость.</p>
                <livewire:service-request-form :service-id="$service->id" />
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section section--bone">
            <div class="wrap">
                <div class="section-head"><h2>Может пригодиться</h2></div>
                <div class="grid grid-3">
                    @foreach ($related as $item)
                        <a href="{{ route('services.show', $item) }}" class="service-card" wire:navigate>
                            <x-landmark :name="$item->landmark ?? 'eiffel'" />
                            <span class="tag">{{ $item->categoryLabel() }}</span>
                            <h3>{{ $item->title }}</h3>
                            <p class="muted small">{{ $item->excerpt }}</p>
                            <div class="price">{{ $item->priceLabel() }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
