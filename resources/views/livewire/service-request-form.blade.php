<div>
    @if ($sent)
        <div class="alert">{{ __('Спасибо! Заявка отправлена, мы свяжемся с вами в ближайшее время.') }}</div>
        <button class="btn btn--ghost btn--sm" wire:click="$set('sent', false)">{{ __('Отправить ещё одну') }}</button>
    @else
        <form wire:submit="submit" class="form">
            <div class="form-row">
                <div>
                    <label for="rq-name">{{ __('Имя') }}</label>
                    <input id="rq-name" class="input" wire:model="name" autocomplete="name">
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="rq-phone">{{ __('Телефон') }}</label>
                    <input id="rq-phone" class="input" wire:model="phone" type="tel" autocomplete="tel" placeholder="+374">
                    @error('phone')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="rq-email">Email</label>
                    <input id="rq-email" class="input" wire:model="email" type="email" autocomplete="email">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="rq-service">{{ __('Услуга') }}</label>
                    <select id="rq-service" class="input" wire:model="serviceId">
                        <option value="">{{ __('Пока не знаю') }}</option>
                        @foreach ($services as $s)
                            <option value="{{ $s->id }}">{{ $s->tr('title') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="rq-message">{{ __('Задача') }}</label>
                <textarea id="rq-message" class="input" wire:model="message" placeholder="{{ __('Объект, площадь, сроки, пожелания') }}"></textarea>
                @error('message')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Отправить заявку') }}</button>
            </div>
        </form>
    @endif
</div>
