<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>Главная</a> / Бренды</div>
            <div class="eyebrow">Витрина</div>
            <h1>Отдельные и эксклюзивные бренды</h1>
            <p class="lead">Мебель, свет, камень, текстиль и материалы, которые мы представляем и привозим для наших проектов.</p>
        </div>
        <x-landmark name="louvre" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            <div class="toolbar">
                <div class="filters" style="margin:0">
                    <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">Все категории</button>
                    @foreach ($categories as $c)
                        <button class="chip {{ $category === $c ? 'active' : '' }}" wire:click="$set('category', @js($c))">{{ $c }}</button>
                    @endforeach
                </div>
                <label class="check" style="margin:0"><input type="checkbox" wire:model.live="exclusive"> Только эксклюзивные</label>
            </div>

            <div class="grid grid-3">
                @forelse ($brands as $brand)
                    <div class="brand-card" wire:key="b-{{ $brand->id }}">
                        @if ($brand->is_exclusive)<span class="badge-ex">Эксклюзив</span>@endif
                        <div class="brand-logo">
                            @if ($brand->logo_url)
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy">
                            @else
                                <span class="brand-word">{{ $brand->name }}</span>
                            @endif
                        </div>
                        <div class="meta">{{ $brand->category }}@if($brand->country) · {{ $brand->country }}@endif</div>
                        @if ($brand->tagline)<h3>{{ $brand->tagline }}</h3>@endif
                        <p class="muted small">{{ $brand->description }}</p>
                        <div style="margin-top:auto;display:flex;gap:16px;flex-wrap:wrap">
                            <a href="{{ route('imports', ['brand' => $brand->name]) }}" class="link-arrow" wire:navigate>Запросить импорт</a>
                            @if ($brand->website)<a href="{{ $brand->website }}" target="_blank" rel="noopener" class="link-arrow">Сайт</a>@endif
                        </div>
                    </div>
                @empty
                    <div class="empty">Бренды не найдены.</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
