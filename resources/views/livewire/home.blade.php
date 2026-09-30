@php
    $icons = [
        'architecture' => '<path d="M4 36V14L20 4l16 10v22M4 36h32M14 36V22h12v14M20 4v6"/>',
        'design' => '<path d="M4 26h32v6H4zM7 26v-7a4 4 0 014-4h18a4 4 0 014 4v7M8 32v4M32 32v4M20 15V5M14 5h12"/>',
        'renovation' => '<path d="M4 36h32M8 36V20h24v16M8 20l12-10 12 10M16 36v-8h8v8M26 6l6 6"/>',
        'cleaning' => '<path d="M20 4v14M12 18h16l3 18H9zM15 26v10M20 26v10M25 26v10"/>',
    ];
    $calcServices = $services->whereNotNull('price_from')->where('price_unit', 'м²');
@endphp
<div>
    {{-- Hero --}}
    <section class="hx">
        <div class="hx-text">
            <div class="eyebrow">Rennova by Metruminvest · {{ __('Ереван') }}</div>
            <h1>{{ __('Пространства, которые') }} <em>{{ __('обновляют жизнь') }}</em></h1>
            <p class="lead">{{ __('Архитектура, дизайн интерьера, ремонт под ключ и клининг. Один договор, прозрачная смета и личный менеджер от замера до сдачи.') }}</p>
            <div class="hero-actions">
                <a href="{{ route('calculator') }}" class="btn" wire:navigate>{{ __('Рассчитать стоимость') }} <span aria-hidden="true">→</span></a>
                <a href="{{ route('booking') }}" class="btn btn--ghost" wire:navigate>{{ __('Записаться на замер') }}</a>
            </div>
            <div class="hx-stats">
                <div><strong>4</strong><span>{{ __('направления в одной команде') }}</span></div>
                <div><strong>1</strong><span>{{ __('договор на полный цикл') }}</span></div>
                <div><strong>{{ $brands->where('is_exclusive', true)->count() }}+</strong><span>{{ __('эксклюзивных брендов') }}</span></div>
            </div>
        </div>
        <div class="hx-photo" style="background-image:url('{{ asset('img/brand/living.jpg') }}')">
            <div class="hx-badge"><x-logo-mark /></div>
            @if ($bundle)
                <a href="{{ route('services.show', $bundle) }}" class="hx-cap" wire:navigate>
                    <span><strong>{{ $bundle->tr('title') }}</strong>{{ __('Дизайн, ремонт и меблировка под ключ') }}</span>
                    <span class="circ" aria-hidden="true">→</span>
                </a>
            @endif
        </div>
    </section>

    {{-- Quick estimate --}}
    @if ($calcServices->isNotEmpty())
        <section class="qcalc">
            <form class="wrap" method="GET" action="{{ route('calculator') }}">
                <div class="qcalc-title">{{ __('Узнайте стоимость') }}<span>{{ __('за минуту, без звонков') }}</span></div>
                <label class="qf">{{ __('Тип объекта') }}
                    <select name="property">
                        @foreach (config('rennova.calculator.property') as $key => [$label])
                            <option value="{{ $key }}">{{ __($label) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="qf">{{ __('Площадь, м²') }}
                    <input type="number" name="area" value="80" min="{{ config('rennova.calculator.min_area') }}" max="{{ config('rennova.calculator.max_area') }}">
                </label>
                <label class="qf">{{ __('Услуга') }}
                    <select name="service">
                        @foreach ($calcServices as $s)
                            <option value="{{ $s->slug }}" @selected($s->category === 'renovation')>{{ $s->tr('title') }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="btn btn--light">{{ __('Рассчитать') }} <span aria-hidden="true">→</span></button>
            </form>
        </section>
    @endif

    {{-- Services --}}
    <section class="section">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <div class="eyebrow">{{ __('Услуги') }}</div>
                    <h2>{{ __('Четыре направления, отдельно или вместе') }}</h2>
                </div>
                <div style="max-width:400px">
                    <p class="muted">{{ __('Берём одну задачу или весь цикл. В смете видна каждая позиция.') }}</p>
                    <a href="{{ route('services.index') }}" class="link-arrow" wire:navigate>{{ __('Все услуги') }}</a>
                </div>
            </div>
            <div class="svc-grid">
                @foreach ($services as $i => $service)
                    <a href="{{ route('services.show', $service) }}" class="svc" wire:navigate>
                        <span class="num">0{{ $i + 1 }}</span>
                        <svg class="ic" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">{!! $icons[$service->category] ?? $icons['renovation'] !!}</svg>
                        <h3>{{ $service->tr('title') }}</h3>
                        <p>{{ $service->tr('excerpt') }}</p>
                        <div class="svc-foot"><strong>{{ $service->priceLabel() }}</strong><span class="link-arrow">{{ __('Подробнее') }}</span></div>
                    </a>
                @endforeach
            </div>

            @if ($bundle)
                <div class="bundle-v3">
                    <div class="bundle-v3-body">
                        <span class="tag tag--light">{{ __('Комплексная услуга') }}</span>
                        <h2>{{ $bundle->tr('title') }}</h2>
                        <p>{{ $bundle->tr('excerpt') }}</p>
                        <ol class="bundle-steps">
                            @foreach ($bundle->tr('features') ?? [] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ol>
                        <a href="{{ route('services.show', $bundle) }}" class="btn btn--light" wire:navigate>{{ __('Узнать о полном цикле') }}</a>
                    </div>
                    <div class="bundle-v3-photo" style="background-image:url('{{ asset('img/brand/window.jpg') }}')"></div>
                </div>
            @endif
        </div>
    </section>

    {{-- Process --}}
    <section class="process">
        <div class="process-photo" style="background-image:url('{{ asset('img/brand/corridor.jpg') }}')"></div>
        <div class="process-body">
            <div class="eyebrow">{{ __('Как мы работаем') }}</div>
            <h2>{{ __('Прозрачный процесс от замера до ключей') }}</h2>
            <ol class="steps-v3">
                <li><strong>{{ __('Замер и бриф') }}</strong><span>{{ __('Приезжаем на объект, фиксируем задачи, сроки и бюджет.') }}</span></li>
                <li><strong>{{ __('Проект и смета') }}</strong><span>{{ __('Дизайн-проект, 3D-визуализация и подробная смета до начала работ.') }}</span></li>
                <li><strong>{{ __('Ремонт с отчётами') }}</strong><span>{{ __('Этапы и фото с объекта в личном кабинете клиента.') }}</span></li>
                <li><strong>{{ __('Сдача объекта') }}</strong><span>{{ __('Финальный клининг, расстановка мебели и передача ключей.') }}</span></li>
            </ol>
            <a href="{{ route('booking') }}" class="btn btn--dark" wire:navigate>{{ __('Записаться на консультацию') }}</a>
        </div>
    </section>

    {{-- Portfolio --}}
    @if ($projects->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">{{ __('Портфолио') }}</div>
                        <h2>{{ __('Реализованные проекты') }}</h2>
                    </div>
                    <a href="{{ route('portfolio') }}" class="btn btn--ghost btn--sm" wire:navigate>{{ __('Все проекты') }} →</a>
                </div>
                <div class="pf-grid">
                    @foreach ($projects as $project)
                        <a href="{{ route('portfolio.show', $project) }}" class="pf-card" wire:navigate wire:key="hp-{{ $project->id }}">
                            @if ($project->coverUrl())
                                <img src="{{ $project->coverUrl() }}" alt="{{ $project->tr('title') }}" loading="lazy">
                            @else
                                <div class="project-placeholder"><x-landmark :name="$project->landmark ?? 'cascade'" stroke="0.9" /></div>
                            @endif
                            @if ($project->hasBeforeAfter())<span class="pf-tag">{{ __('До / после') }}</span>@endif
                            <span class="pf-cap">
                                <span><strong>{{ $project->tr('title') }}</strong>{{ $project->categoryLabel() }}@if($project->area) · {{ $project->area }} {{ __('м²') }}@endif</span>
                                <span class="circ" aria-hidden="true">→</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Brands --}}
    @if ($brands->isNotEmpty())
        <section class="section section--bone section--tight">
            <div class="wrap">
                <div class="section-head" style="margin-bottom:32px">
                    <div>
                        <div class="eyebrow">{{ __('Эксклюзивные бренды') }}</div>
                        <h2>{{ __('Материалы и предметы, которые мы представляем в Армении') }}</h2>
                    </div>
                    <a href="{{ route('brands') }}" class="btn btn--ghost btn--sm" wire:navigate>{{ __('Витрина брендов') }} →</a>
                </div>
                <div class="brand-row">
                    @foreach ($brands->take(5) as $brand)
                        <a href="{{ route('brands.show', $brand) }}" wire:navigate>{{ $brand->name }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Reviews --}}
    @if ($reviews->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">{{ __('Отзывы') }}</div>
                        <h2>{{ __('Что говорят клиенты') }}</h2>
                    </div>
                    <a href="{{ route('reviews') }}" class="link-arrow" wire:navigate>{{ __('Все отзывы') }}</a>
                </div>
                <div class="grid grid-3">
                    @foreach ($reviews as $review)
                        @include('partials.review-card')
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Blog --}}
    @if ($posts->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">{{ __('Блог') }}</div>
                        <h2>{{ __('Советы и идеи для вашего пространства') }}</h2>
                    </div>
                    <a href="{{ route('blog') }}" class="link-arrow" wire:navigate>{{ __('Все статьи') }}</a>
                </div>
                <div class="grid grid-3">
                    @foreach ($posts as $post)
                        @include('partials.post-card')
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Request --}}
    <section class="req" id="request">
        <div class="req-body">
            <div class="eyebrow">{{ __('Бесплатная консультация') }}</div>
            <h2>{{ __('Расскажите о своём проекте') }}</h2>
            <p class="lead">{{ __('Оставьте контакты, и менеджер свяжется с вами, чтобы обсудить задачу, сроки и бюджет.') }}</p>
            <div class="panel">
                <livewire:service-request-form />
            </div>
        </div>
        <div class="req-photo" style="background-image:url('{{ asset('img/brand/building.jpg') }}')"></div>
    </section>
</div>
