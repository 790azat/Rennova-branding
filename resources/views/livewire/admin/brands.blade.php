<div>
    <div class="admin-top">
        <h1>Бренды</h1>
        <button class="btn" wire:click="create">Добавить бренд</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Бренд</th><th>Категория</th><th>Эксклюзив</th><th>На главной</th><th>Показ</th><th></th></tr></thead>
            <tbody>
            @forelse ($brands as $b)
                <tr wire:key="ab-{{ $b->id }}">
                    <td><strong>{{ $b->name }}</strong><div class="small muted">{{ $b->country }}</div></td>
                    <td class="small">{{ $b->category }}</td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_exclusive) wire:click="toggle({{ $b->id }}, 'is_exclusive')"></label></td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_featured) wire:click="toggle({{ $b->id }}, 'is_featured')"></label></td>
                    <td><label class="check"><input type="checkbox" @checked($b->is_active) wire:click="toggle({{ $b->id }}, 'is_active')"></label></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $b->id }})">Изменить</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $b->id }})" wire:confirm="Удалить бренд?">Удалить</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Брендов пока нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ $editingId ? 'Изменить бренд' : 'Новый бренд' }}</h2>
                <form wire:submit="save" class="form">
                    <div class="form-row">
                        <div><label>Название</label><input class="input" wire:model="form.name">@error('form.name')<div class="error">{{ $message }}</div>@enderror @error('slug')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Страна</label><input class="input" wire:model="form.country"></div>
                    </div>
                    <div class="form-row">
                        <div><label>Категория</label><input class="input" wire:model="form.category" placeholder="Мебель, Освещение…"></div>
                        <div><label>Слоган</label><input class="input" wire:model="form.tagline"></div>
                    </div>
                    <div><label>Описание</label><textarea class="input" wire:model="form.description"></textarea></div>
                    <div class="form-row">
                        <div><label>Сайт</label><input class="input" wire:model="form.website" placeholder="https://">@error('form.website')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Ссылка на логотип</label><input class="input" wire:model="form.logo_url" placeholder="https://…/logo.svg">@error('form.logo_url')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="form-row">
                        <div><label>Порядок</label><input class="input" type="number" wire:model="form.sort"></div>
                        <div style="display:flex;gap:20px;align-items:end;padding-bottom:12px;flex-wrap:wrap">
                            <label class="check"><input type="checkbox" wire:model="form.is_exclusive"> Эксклюзив</label>
                            <label class="check"><input type="checkbox" wire:model="form.is_featured"> На главной</label>
                            <label class="check"><input type="checkbox" wire:model="form.is_active"> Показывать</label>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn" type="submit">Сохранить</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
