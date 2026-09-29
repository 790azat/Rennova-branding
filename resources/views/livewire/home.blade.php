<div>
    {{-- Hero --}}
    <section class="hero">
        <div class="wrap hero-grid">
            <div>
                <div class="eyebrow">Rennova by Metruminvest</div>
                <h1>Пространства, которые <em>обновляют</em> жизнь</h1>
                <p class="lead">Ремонт под ключ, дизайн интерьера, архитектура и клининг. Отдельной услугой или полным циклом: от концепции до финальной сдачи.</p>
                <div class="hero-actions">
                    <a href="{{ route('services.index') }}" class="btn btn--light" wire:navigate>Выбрать услугу</a>
                    <a href="#request" class="btn btn--ghost" style="color:var(--bone);border-color:rgba(236,235,225,.4)">Оставить заявку</a>
                </div>
            </div>
            <div class="hero-mark">
                <svg class="outline" viewBox="0 0 81 78" aria-hidden="true"><path d="M0 0H15L26 11L40 0H81V24H45L81 60V78H74L25 43V78H0Z"/></svg>
                <x-logo-mark />
            </div>
        </div>
        <div class="wrap">
            <div class="hero-stats">
                <div><strong>4</strong><span>направления под одной крышей</span></div>
                <div><strong>1</strong><span>договор на полный цикл</span></div>
                <div><strong>{{ $brands->where('is_exclusive', true)->count() }}+</strong><span>эксклюзивных брендов</span></div>
                <div><strong>∞</strong><span>вдохновения от архитектуры мира</span></div>
            </div>
            <div class="skyline">
                @foreach (array_keys(config('rennova.landmarks')) as $landmark)
                    <x-landmark :name="$landmark" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="section" id="about">
        <div class="wrap split">
            <div>
                <div class="eyebrow">О компании</div>
                <h2>Rennova превращает пространство в нечто новое и совершенное</h2>
                <p class="lead">Мы армянская компания, специализирующаяся на ремонте под ключ и трансформации жилой и коммерческой недвижимости. От концепции и дизайна до строительства, меблировки и финальной сдачи.</p>
                <div class="meaning">
                    <div><strong>R</strong><span>Современная интерпретация буквы «R», символ Rennova.</span></div>
                    <div><strong>□</strong><span>Квадратные формы: стабильность, надёжность и сила.</span></div>
                    <div><strong>↖</strong><span>Стрелка: обновление, трансформация и новая жизнь пространства.</span></div>
                </div>
            </div>
            <div class="frame">
                <svg class="lines" viewBox="0 0 100 125" fill="none" stroke="currentColor" stroke-width=".3" aria-hidden="true">
                    @for ($i = 0; $i <= 10; $i++)<path d="M{{ $i * 10 }} 0V125"/>@endfor
                    @for ($i = 0; $i <= 12; $i++)<path d="M0 {{ $i * 10.4 }}H100"/>@endfor
                </svg>
                <x-logo-mark />
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="section section--bone">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Услуги</div>
                    <h2>Четыре направления, отдельно или вместе</h2>
                </div>
                <a href="{{ route('services.index') }}" class="link-arrow" wire:navigate>Все услуги</a>
            </div>
            <div class="grid grid-4">
                @foreach ($services as $i => $service)
                    <a href="{{ route('services.show', $service) }}" class="service-card" wire:navigate>
                        <x-landmark :name="$service->landmark ?? 'eiffel'" />
                        <div class="num">0{{ $i + 1 }}</div>
                        <span class="tag">{{ $service->categoryLabel() }}</span>
                        <h3>{{ $service->title }}</h3>
                        <p class="muted small">{{ $service->excerpt }}</p>
                        <div class="price">{{ $service->priceLabel() }}</div>
                        <span class="link-arrow">Подробнее</span>
                    </a>
                @endforeach
            </div>

            @if ($bundle)
                <div class="bundle" style="margin-top:24px">
                    <div class="bundle-body">
                        <span class="tag tag--solid" style="background:var(--olive);border-color:var(--olive)">Комплексная услуга</span>
                        <h2>{{ $bundle->title }}</h2>
                        <p style="color:#c9ccbf">{{ $bundle->excerpt }}</p>
                        <ol class="bundle-steps">
                            @foreach ($bundle->features ?? [] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ol>
                        <a href="{{ route('services.show', $bundle) }}" class="btn btn--light" wire:navigate>Узнать о полном цикле</a>
                    </div>
                    <div class="bundle-art">
                        <x-logo-mark />
                        <x-landmark :name="$bundle->landmark ?? 'burj'" stroke="0.8" />
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Brands --}}
    @if ($brands->isNotEmpty())
        <section class="section section--dark">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">Эксклюзивные бренды</div>
                        <h2>Материалы и предметы, которые мы представляем в Армении</h2>
                    </div>
                    <a href="{{ route('brands') }}" class="btn btn--light" wire:navigate>Витрина брендов</a>
                </div>
            </div>
            <div class="brand-marquee">
                <div class="track">
                    @foreach ([1, 2] as $copy)
                        @foreach ($brands as $brand)
                            <span>{{ $brand->name }}</span>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <div class="wrap" style="margin-top:56px">
                <div class="grid grid-3">
                    @foreach ($brands->where('is_featured', true)->take(3) as $brand)
                        <div class="brand-card">
                            @if ($brand->is_exclusive)<span class="badge-ex">Эксклюзив</span>@endif
                            <div class="brand-logo"><span class="brand-word">{{ $brand->name }}</span></div>
                            <div class="meta">{{ $brand->category }} · {{ $brand->country }}</div>
                            <p class="muted small">{{ $brand->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Landmarks --}}
    <section class="section">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Вдохновение</div>
                    <h2>Архитектура мира в каждой детали</h2>
                </div>
                <p class="lead" style="max-width:420px">Линии великих зданий стали частью нашего визуального языка: мы учимся у них пропорциям, ритму и свету.</p>
            </div>
            <div class="landmarks">
                @foreach (config('rennova.landmarks') as $key => [$name, $city, $year, $note])
                    <div class="landmark-cell">
                        <x-landmark :name="$key" />
                        <div>
                            <div class="city">{{ $city }} · {{ $year }}</div>
                            <h3>{{ $name }}</h3>
                            <p>{{ $note }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Community --}}
    <section class="section section--bone">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="eyebrow">Сообщество</div>
                <h2>Обсуждайте проекты и заказывайте особые товары</h2>
                <p class="lead">Зарегистрированные пользователи обсуждают дизайн, архитектуру и ремонт с нашими специалистами, а также отправляют запросы на импорт специальных товаров.</p>
                <div class="hero-actions">
                    <a href="{{ route('discussions.index') }}" class="btn" wire:navigate>К обсуждениям</a>
                    <a href="{{ route('imports') }}" class="btn btn--ghost" wire:navigate>Запросить импорт</a>
                </div>
            </div>
            <div>
                @forelse ($discussions as $d)
                    <div class="topic">
                        <div class="avatar">{{ $d->user->initials() }}</div>
                        <div>
                            <h3><a href="{{ route('discussions.show', $d) }}" wire:navigate>{{ $d->title }}</a></h3>
                            <div class="small muted">{{ $d->categoryLabel() }} · {{ $d->user->name }}</div>
                        </div>
                        <div class="stats"><strong>{{ $d->replies_count }}</strong>ответов</div>
                    </div>
                @empty
                    <div class="empty">Пока нет обсуждений. Начните первое!</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Request --}}
    <section class="section section--dark" id="request">
        <div class="wrap split">
            <div>
                <div class="eyebrow">Заявка</div>
                <h2>Расскажите о своём проекте</h2>
                <p class="lead">Оставьте контакты, и менеджер свяжется с вами, чтобы обсудить задачу, сроки и бюджет.</p>
                <div class="skyline" style="color:rgba(236,235,225,.2);max-width:420px;margin-top:40px">
                    <x-landmark name="cascade" /><x-landmark name="colosseum" /><x-landmark name="taj" />
                </div>
            </div>
            <div class="panel">
                <livewire:service-request-form />
            </div>
        </div>
    </section>
</div>
