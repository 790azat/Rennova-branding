<div>
    <div class="admin-top"><h1>{{ __('Настройки и соцсети') }}</h1></div>
    <div class="panel" style="max-width:760px">
        @if ($saved)<div class="alert">{{ __('Настройки сохранены.') }}</div>@endif
        <form wire:submit="save" class="form">
            @foreach (\App\Models\Setting::FIELDS as $key => [$label, $default])
                <div>
                    <label>{{ __($label) }}</label>
                    <input class="input" wire:model="values.{{ $key }}" placeholder="{{ $default ?: 'https://' }}">
                    @error('values.'.$key)<div class="error">{{ $message }}</div>@enderror
                </div>
            @endforeach
            <p class="small muted">{{ __('Пока ссылка на Facebook или Instagram пустая, иконка на сайте показывается приглушённой и никуда не ведёт.') }}</p>
            <div><button class="btn" type="submit">{{ __('Сохранить') }}</button></div>
        </form>
    </div>
</div>
