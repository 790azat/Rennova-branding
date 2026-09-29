<div>
    <div class="admin-top"><h1>Настройки и соцсети</h1></div>
    <div class="panel" style="max-width:760px">
        @if ($saved)<div class="alert">Настройки сохранены.</div>@endif
        <form wire:submit="save" class="form">
            @foreach (\App\Models\Setting::FIELDS as $key => [$label, $default])
                <div>
                    <label>{{ $label }}</label>
                    <input class="input" wire:model="values.{{ $key }}" placeholder="{{ $default ?: 'https://' }}">
                    @error('values.'.$key)<div class="error">{{ $message }}</div>@enderror
                </div>
            @endforeach
            <p class="small muted">Пока ссылка на Facebook или Instagram пустая, иконка на сайте показывается приглушённой и никуда не ведёт.</p>
            <div><button class="btn" type="submit">Сохранить</button></div>
        </form>
    </div>
</div>
