<div class="auth">
    <div class="auth-art">
        <x-logo-mark style="width:56px;color:var(--bone)" />
        <div style="position:relative">
            <div class="eyebrow">{{ __('Личный кабинет') }}</div>
            <h2>{{ __('С возвращением в Rennova') }}</h2>
            <p style="color:#c9ccbf;max-width:380px">{{ __('Обсуждайте проекты, отслеживайте заявки и запросы на импорт.') }}</p>
        </div>
        <x-landmark name="eiffel" stroke="0.8" />
    </div>
    <div class="auth-form">
        <div class="inner">
            <h2>{{ __('Вход') }}</h2>
            <p class="muted">{{ __('Нет аккаунта?') }} <a href="{{ route('register') }}" style="color:var(--olive);font-weight:600" wire:navigate>{{ __('Зарегистрироваться') }}</a></p>
            <form wire:submit="login" class="form" style="margin-top:28px">
                <div>
                    <label for="email">Email</label>
                    <input id="email" class="input" type="email" wire:model="email" autocomplete="email" autofocus>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password">{{ __('Пароль') }}</label>
                    <input id="password" class="input" type="password" wire:model="password" autocomplete="current-password">
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>
                <label class="check"><input type="checkbox" wire:model="remember"> {{ __('Запомнить меня') }}</label>
                <button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Войти') }}</button>
            </form>
        </div>
    </div>
</div>
