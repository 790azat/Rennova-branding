<div class="auth">
    <div class="auth-art">
        <x-logo-mark style="width:56px;color:var(--bone)" />
        <div style="position:relative">
            <div class="eyebrow">{{ __('Присоединяйтесь') }}</div>
            <h2>{{ __('Аккаунт Rennova') }}</h2>
            <p style="color:#c9ccbf;max-width:380px">{{ __('Участвуйте в обсуждениях дизайна, архитектуры и ремонта, отправляйте запросы на импорт особых товаров.') }}</p>
        </div>
        <x-landmark name="burj" stroke="0.8" />
    </div>
    <div class="auth-form">
        <div class="inner">
            <h2>{{ __('Регистрация') }}</h2>
            <p class="muted">{{ __('Уже есть аккаунт?') }} <a href="{{ route('login') }}" style="color:var(--olive);font-weight:600" wire:navigate>{{ __('Войти') }}</a></p>
            <form wire:submit="register" class="form" style="margin-top:28px">
                <div>
                    <label for="name">{{ __('Имя и фамилия') }}</label>
                    <input id="name" class="input" wire:model="name" autocomplete="name">
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div>
                        <label for="email">Email</label>
                        <input id="email" class="input" type="email" wire:model="email" autocomplete="email">
                        @error('email')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="phone">{{ __('Телефон') }}</label>
                        <input id="phone" class="input" type="tel" wire:model="phone" autocomplete="tel">
                        @error('phone')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="password">{{ __('Пароль') }}</label>
                        <input id="password" class="input" type="password" wire:model="password" autocomplete="new-password">
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation">{{ __('Повторите пароль') }}</label>
                        <input id="password_confirmation" class="input" type="password" wire:model="password_confirmation" autocomplete="new-password">
                    </div>
                </div>
                <button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Создать аккаунт') }}</button>
            </form>
        </div>
    </div>
</div>
