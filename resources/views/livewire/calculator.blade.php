@php $cfg = config('rennova.calculator'); $money = fn ($v) => number_format($v, 0, ',', ' ').' ֏'; @endphp
<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Калькулятор') }}</div>
            <div class="eyebrow">{{ __('Калькулятор') }}</div>
            <h1>{{ __('Сколько стоит ваш проект') }}</h1>
            <p class="lead">{{ __('Выберите услугу, площадь и параметры объекта. Мы покажем ориентировочную стоимость, а менеджер уточнит смету после осмотра.') }}</p>
        </div>
        <x-landmark name="bigben" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap calc">
            <div class="panel calc-form">
                @if ($services->isEmpty())
                    <div class="empty">{{ __('Калькулятор скоро заработает.') }}</div>
                @else
                <div class="form">
                    <div>
                        <label>{{ __('Услуга') }}</label>
                        <div class="choice-grid">
                            @foreach ($services as $s)
                                <label class="choice {{ $serviceSlug === $s->slug ? 'active' : '' }}">
                                    <input type="radio" wire:model.live="serviceSlug" value="{{ $s->slug }}">
                                    <strong>{{ $s->tr('title') }}</strong>
                                    <span>{{ $s->priceLabel() }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="calc-area">{{ __('Площадь, м²') }}</label>
                        <div class="area-row">
                            <input type="range" min="10" max="400" step="5" wire:model.live="area" aria-label="{{ __('Площадь, м²') }}">
                            <input id="calc-area" class="input" type="number" min="{{ $cfg['min_area'] }}" max="{{ $cfg['max_area'] }}" wire:model.live.debounce.400ms="area">
                        </div>
                        @error('area')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div>
                            <label>{{ __('Тип объекта') }}</label>
                            <select class="input" wire:model.live="property">
                                @foreach ($cfg['property'] as $key => [$label])<option value="{{ $key }}">{{ __($label) }}</option>@endforeach
                            </select>
                        </div>
                        @if ($this->usesCondition())
                            <div>
                                <label>{{ __('Состояние') }}</label>
                                <select class="input" wire:model.live="condition">
                                    @foreach ($cfg['condition'] as $key => [$label])<option value="{{ $key }}">{{ __($label) }}</option>@endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    @if ($this->availableExtras())
                        <div>
                            <label>{{ __('Дополнительно') }}</label>
                            <div class="checks">
                                @foreach ($this->availableExtras() as $key => [$label, $price])
                                    <label class="check"><input type="checkbox" wire:model.live="extras" value="{{ $key }}"> {{ __($label) }} <span class="muted small">+{{ $money($price) }}/{{ __('м²') }}</span></label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <label class="check"><input type="checkbox" wire:model.live="urgent"> {{ __('Срочно: нужно начать в ближайшие дни') }}</label>
                </div>
                @endif
            </div>

            <aside class="calc-result">
                <div class="eyebrow">{{ __('Ориентировочно') }}</div>
                @if ($estimate)
                    <div class="calc-sum">{{ $money($estimate[0]) }} <span>— {{ $money($estimate[1]) }}</span></div>
                    <p class="small" style="color:#c9ccbf">{{ __('Точная смета зависит от материалов и состояния объекта. Это не публичная оферта.') }}</p>
                @else
                    <div class="calc-sum">—</div>
                    <p class="small" style="color:#c9ccbf">{{ __('Укажите площадь от :min до :max м².', ['min' => $cfg['min_area'], 'max' => $cfg['max_area']]) }}</p>
                @endif

                @if ($sent)
                    <div class="alert" style="margin-top:24px">{{ __('Спасибо! Расчёт отправлен менеджеру, мы свяжемся с вами.') }}</div>
                    <button class="btn btn--light" wire:click="$set('sent', false)">{{ __('Новый расчёт') }}</button>
                @elseif ($estimate)
                    <form wire:submit="submit" class="form" style="margin-top:28px">
                        <div><input class="input" wire:model="name" placeholder="{{ __('Имя') }}" aria-label="{{ __('Имя') }}">@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><input class="input" wire:model="phone" placeholder="{{ __('Телефон') }}" aria-label="{{ __('Телефон') }}">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><input class="input" type="email" wire:model="email" placeholder="Email" aria-label="Email">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><textarea class="input" style="min-height:80px" wire:model="comment" placeholder="{{ __('Комментарий') }}" aria-label="{{ __('Комментарий') }}"></textarea></div>
                        <button class="btn btn--light" type="submit" wire:loading.attr="disabled">{{ __('Отправить расчёт менеджеру') }}</button>
                    </form>
                @endif
            </aside>
        </div>
    </section>
</div>
