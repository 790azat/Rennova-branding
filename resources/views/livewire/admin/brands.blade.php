<div>
    <div class="admin-top">
        <h1>{{ __('Бренды') }}</h1>
        <button class="btn" wire:click="create">{{ __('Добавить бренд') }}</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Бренд') }}</th><th>{{ __('Категория') }}</th><th>{{ __('Эксклюзив') }}</th><th>{{ __('На главной') }}</th><th>{{ __('Показ') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($brands as $b)
                <tr wire:key="ab-{{ $b->id }}">
                    <td><strong>{{ $b->name }}</strong><div class="small muted">{{ $b->country }}</div></td>
                    <td class="small">{{ $b->category }}</td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_exclusive) wire:click="toggle({{ $b->id }}, 'is_exclusive')"></label></td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_featured) wire:click="toggle({{ $b->id }}, 'is_featured')"></label></td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_active) wire:click="toggle({{ $b->id }}, 'is_active')"></label></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $b->id }})">{{ __('Изменить') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $b->id }})" wire:confirm="{{ __('Удалить бренд?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">{{ __('Брендов пока нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ $editingId ? __('Изменить бренд') : __('Новый бренд') }}</h2>
                <p class="muted" style="margin:-6px 0 14px;font-size:13px">{{ __('Основной текст — на русском, переводы внизу формы.') }}</p>
                <form wire:submit="save" class="form">
                    <div class="form-row">
                        <div><label>{{ __('Название') }}</label><input class="input" wire:model="form.name">@error('form.name')<div class="error">{{ $message }}</div>@enderror @error('slug')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Страна') }}</label><input class="input" wire:model="form.country"></div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Категория') }}</label><input class="input" wire:model="form.category" placeholder="{{ __('Мебель, Освещение…') }}"></div>
                        <div><label>{{ __('Слоган') }}</label><input class="input" wire:model="form.tagline"></div>
                    </div>
                    <div><label>{{ __('Описание') }}</label><textarea class="input" wire:model="form.description"></textarea></div>
                    <div class="form-row">
                        <div><label>{{ __('Сайт') }}</label><input class="input" wire:model="form.website" placeholder="https://">@error('form.website')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Ссылка на логотип') }}</label><input class="input" wire:model="form.logo_url" placeholder="https://…/logo.svg">@error('form.logo_url')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Порядок') }}</label><input class="input" type="number" wire:model="form.sort"></div>
                        <div style="display:flex;gap:20px;align-items:end;padding-bottom:12px;flex-wrap:wrap">
                            <label class="check"><input type="checkbox" wire:model="form.is_exclusive"> {{ __('Эксклюзив') }}</label>
                            <label class="check"><input type="checkbox" wire:model="form.is_featured"> {{ __('На главной') }}</label>
                            <label class="check"><input type="checkbox" wire:model="form.is_active"> {{ __('Показывать') }}</label>
                        </div>
                    </div>
                    @include('partials.admin.translations', ['fields' => ['tagline' => 'Слоган', 'category' => 'Категория', 'country' => 'Страна', 'description' => 'Описание'], 'long' => ['description']])
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
