<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>Главная</a> / Импорт товаров</div>
            <div class="eyebrow">Импорт</div>
            <h1>Привезём то, чего нет в Армении</h1>
            <p class="lead">Редкий камень, дизайнерский свет, мебель от конкретной фабрики или сантехника под заказ. Опишите, что нужно, и мы рассчитаем поставку.</p>
        </div>
        <x-landmark name="colosseum" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="eyebrow">Как это работает</div>
                <div class="meaning">
                    <div><strong>01</strong><span>Вы описываете товар, желаемый бренд, количество и бюджет.</span></div>
                    <div><strong>02</strong><span>Мы находим поставщика и присылаем расчёт с доставкой.</span></div>
                    <div><strong>03</strong><span>После подтверждения заказываем и доставляем на объект.</span></div>
                </div>

                @auth
                    <h3 style="margin-top:48px">Мои запросы</h3>
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
                                <div class="alert" style="margin:14px 0 0">Ответ Rennova: {{ $r->admin_note }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="muted">Вы ещё не отправляли запросов.</p>
                    @endforelse
                @endauth
            </div>

            <div class="panel">
                @auth
                    @if ($sent)
                        <div class="alert">Запрос отправлен. Статус появится в списке «Мои запросы».</div>
                    @endif
                    <h3>Новый запрос на импорт</h3>
                    <form wire:submit="submit" class="form">
                        <div class="form-row">
                            <div>
                                <label>Тип товара</label>
                                <select class="input" wire:model="productType">
                                    @foreach (\App\Models\ImportRequest::PRODUCT_TYPES as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Бренд (если есть)</label>
                                <input class="input" wire:model="preferredBrand">
                                @error('preferredBrand')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div>
                            <label>Что нужно привезти</label>
                            <input class="input" wire:model="title" placeholder="Например: мраморные слэбы Calacatta">
                            @error('title')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label>Описание</label>
                            <textarea class="input" wire:model="description" placeholder="Размеры, цвет, артикул, ссылки на товар"></textarea>
                            @error('description')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <div>
                                <label>Количество</label>
                                <input class="input" wire:model="quantity" placeholder="12 м², 4 шт.">
                            </div>
                            <div>
                                <label>Бюджет, ֏</label>
                                <input class="input" type="number" min="0" wire:model="budget">
                                @error('budget')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div><button class="btn" type="submit" wire:loading.attr="disabled">Отправить запрос</button></div>
                    </form>
                @else
                    <h3>Запросы доступны зарегистрированным пользователям</h3>
                    <p class="muted">Войдите или создайте аккаунт, чтобы отправить запрос и отслеживать его статус.</p>
                    <div class="actions">
                        <a href="{{ route('login') }}" class="btn" wire:navigate>Войти</a>
                        <a href="{{ route('register') }}" class="btn btn--ghost" wire:navigate>Регистрация</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</div>
