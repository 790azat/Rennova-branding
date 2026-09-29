<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> {{ __('/ Импорт товаров') }}</div>
            <div class="eyebrow">{{ __('Импорт') }}</div>
            <h1>{{ __('Привезём то, чего нет в Армении') }}</h1>
            <p class="lead">{{ __('Редкий камень, дизайнерский свет, мебель от конкретной фабрики или сантехника под заказ. Опишите, что нужно, и мы рассчитаем поставку.') }}</p>
        </div>
        <x-landmark name="colosseum" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="eyebrow">{{ __('Как это работает') }}</div>
                <div class="meaning">
                    <div><strong>01</strong><span>{{ __('Вы описываете товар, желаемый бренд, количество и бюджет.') }}</span></div>
                    <div><strong>02</strong><span>{{ __('Мы находим поставщика и присылаем расчёт с доставкой.') }}</span></div>
                    <div><strong>03</strong><span>{{ __('После подтверждения заказываем и доставляем на объект.') }}</span></div>
                </div>

                @auth
                    <h3 style="margin-top:48px">{{ __('Мои запросы') }}</h3>
                    @forelse ($requests as $r)
                        <div class="card" style="margin-bottom:12px;padding:20px 24px" wire:key="ir-{{ $r->id }}">
                            <div style="display:flex;justify-content:space-between;gap:12px;align-items:start">
                                <div>
                                    <strong style="color:var(--forest)">{{ $r->title }}</strong>
                                    <div class="small muted">{{ $r->typeLabel() }}@if($r->preferred_brand) · {{ $r->preferred_brand }}@endif · {{ $r->created_at->format('d.m.Y') }}</div>
                                </div>
                                <span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span>
                            </div>
                            @if ($r->admin_note)
                                <div class="alert" style="margin:14px 0 0">{{ __('Ответ Rennova:') }} {{ $r->admin_note }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="muted">{{ __('Вы ещё не отправляли запросов.') }}</p>
                    @endforelse
                @endauth
            </div>

            <div class="panel">
                @auth
                    @if ($sent)
                        <div class="alert">{{ __('Запрос отправлен. Статус появится в списке «Мои запросы».') }}</div>
                    @endif
                    <h3>{{ __('Новый запрос на импорт') }}</h3>
                    <form wire:submit="submit" class="form">
                        <div class="form-row">
                            <div>
                                <label>{{ __('Тип товара') }}</label>
                                <select class="input" wire:model="productType">
                                    @foreach (\App\Models\ImportRequest::PRODUCT_TYPES as $key => $label)
                                        <option value="{{ $key }}">{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>{{ __('Бренд (если есть)') }}</label>
                                <input class="input" wire:model="preferredBrand">
                                @error('preferredBrand')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div>
                            <label>{{ __('Что нужно привезти') }}</label>
                            <input class="input" wire:model="title" placeholder="{{ __('Например: мраморные слэбы Calacatta') }}">
                            @error('title')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label>{{ __('Описание') }}</label>
                            <textarea class="input" wire:model="description" placeholder="{{ __('Размеры, цвет, артикул, ссылки на товар') }}"></textarea>
                            @error('description')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <div>
                                <label>{{ __('Количество') }}</label>
                                <input class="input" wire:model="quantity" placeholder="{{ __('12 м², 4 шт.') }}">
                            </div>
                            <div>
                                <label>{{ __('Бюджет, ֏') }}</label>
                                <input class="input" type="number" min="0" wire:model="budget">
                                @error('budget')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div><button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Отправить запрос') }}</button></div>
                    </form>
                @else
                    <h3>{{ __('Запросы доступны зарегистрированным пользователям') }}</h3>
                    <p class="muted">{{ __('Войдите или создайте аккаунт, чтобы отправить запрос и отслеживать его статус.') }}</p>
                    <div class="actions">
                        <a href="{{ route('login') }}" class="btn" wire:navigate>{{ __('Войти') }}</a>
                        <a href="{{ route('register') }}" class="btn btn--ghost" wire:navigate>{{ __('Регистрация') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</div>
