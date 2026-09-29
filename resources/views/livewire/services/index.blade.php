<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> {{ __('/ Услуги') }}</div>
            <div class="eyebrow">{{ __('Услуги') }}</div>
            <h1>{{ __('Клининг, ремонт, дизайн и архитектура') }}</h1>
            <p class="lead">{{ __('Закажите одну услугу или доверьте нам весь цикл работ с единой сметой и одним ответственным менеджером.') }}</p>
        </div>
        <x-landmark name="empire" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            <div class="filters">
                <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">{{ __('Все') }}</button>
                @foreach (\App\Models\Service::CATEGORIES as $key => $label)
                    <button class="chip {{ $category === $key ? 'active' : '' }}" wire:click="$set('category', '{{ $key }}')">{{ __($label) }}</button>
                @endforeach
            </div>

            <div class="grid grid-3" wire:loading.class="muted">
                @forelse ($services as $i => $service)
                    <a href="{{ route('services.show', $service) }}" class="service-card" wire:navigate wire:key="s-{{ $service->id }}"
                       @if($service->is_bundle) style="background:var(--forest);color:var(--bone)" @endif>
                        <x-landmark :name="$service->landmark ?? 'eiffel'" />
                        <div class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <span class="tag">{{ $service->categoryLabel() }}</span>
                        <h3 @if($service->is_bundle) style="color:var(--bone)" @endif>{{ $service->tr('title') }}</h3>
                        <p class="muted small">{{ $service->tr('excerpt') }}</p>
                        <div class="price">{{ $service->priceLabel() }}</div>
                        <span class="link-arrow" @if($service->is_bundle) style="color:var(--bone)" @endif>{{ __('Подробнее') }}</span>
                    </a>
                @empty
                    <div class="empty">{{ __('В этой категории пока нет услуг.') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
