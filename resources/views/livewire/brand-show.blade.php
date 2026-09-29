<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('brands') }}" wire:navigate>{{ __('Бренды') }}</a></div>
            <div class="eyebrow">{{ $brand->tr('category') }}@if($brand->tr('country')) · {{ $brand->tr('country') }}@endif</div>
            <h1>{{ $brand->name }}</h1>
            @if ($brand->tr('tagline'))<p class="lead">{{ $brand->tr('tagline') }}</p>@endif
            <div class="hero-actions">
                <a href="{{ route('imports', ['brand' => $brand->name]) }}" class="btn btn--light" wire:navigate>{{ __('Запросить импорт') }}</a>
                @if ($brand->website)<a href="{{ $brand->website }}" class="btn btn--ghost" style="color:var(--bone);border-color:rgba(236,235,225,.4)" target="_blank" rel="noopener">{{ __('Сайт бренда') }}</a>@endif
            </div>
        </div>
        <x-landmark name="louvre" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            @if ($brand->tr('description'))<p class="lead" style="margin-bottom:48px">{{ $brand->tr('description') }}</p>@endif

            @if ($categories->count() > 1)
                <div class="filters">
                    <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">{{ __('Все') }}</button>
                    @foreach ($categories as $c => $cLabel)
                        <button class="chip {{ $category === $c ? 'active' : '' }}" wire:click="$set('category', @js($c))">{{ $cLabel }}</button>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-3">
                @forelse ($products as $product)
                    <div class="product-card" wire:key="p-{{ $product->id }}">
                        <div class="product-media">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->tr('name') }}" loading="lazy">
                            @else
                                <span class="brand-word">{{ $brand->name }}</span>
                            @endif
                        </div>
                        <div class="product-body">
                            @if ($product->tr('category'))<div class="meta">{{ $product->tr('category') }}</div>@endif
                            <h3>{{ $product->tr('name') }}</h3>
                            @if ($product->tr('description'))<p class="muted small">{{ $product->tr('description') }}</p>@endif
                            @if ($product->tr('price_note'))<div class="price">{{ $product->tr('price_note') }}</div>@endif
                            <a href="{{ route('imports', ['brand' => $brand->name, 'product' => $product->tr('name')]) }}" class="btn btn--ghost btn--sm" style="margin-top:auto;align-self:flex-start" wire:navigate>{{ __('Запросить импорт') }}</a>
                        </div>
                    </div>
                @empty
                    <div class="empty" style="grid-column:1/-1">{{ __('Каталог бренда скоро появится. Опишите нужный товар, и мы его привезём.') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
