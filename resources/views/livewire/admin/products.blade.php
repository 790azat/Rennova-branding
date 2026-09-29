<div>
    <div class="admin-top">
        <h1>{{ __('Товары брендов') }}</h1>
        <button class="btn" wire:click="create" @disabled($brands->isEmpty())>{{ __('Добавить товар') }}</button>
    </div>
    <div class="filters">
        <button class="chip {{ ! $brand ? 'active' : '' }}" wire:click="$set('brand', null)">{{ __('Все бренды') }}</button>
        @foreach ($brands as $b)
            <button class="chip {{ $brand === $b->id ? 'active' : '' }}" wire:click="$set('brand', {{ $b->id }})">{{ $b->name }}</button>
        @endforeach
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th></th><th>{{ __('Товар') }}</th><th>{{ __('Бренд') }}</th><th>{{ __('Цена / условия') }}</th><th>{{ __('Показ') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($products as $p)
                <tr wire:key="apr-{{ $p->id }}">
                    <td>@if($p->image)<img src="{{ $p->image }}" alt="" class="thumb">@endif</td>
                    <td><strong>{{ $p->name }}</strong><div class="small muted">{{ $p->category }}</div></td>
                    <td class="small"><a href="{{ route('brands.show', $p->brand) }}" target="_blank">{{ $p->brand->name }}</a></td>
                    <td class="small">{{ $p->price_note }}</td>
                    <td><label class="check"><input type="checkbox" @checked($p->is_active) wire:click="toggle({{ $p->id }})"></label></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $p->id }})">{{ __('Изменить') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Удалить товар?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">{{ __('Товаров пока нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ $editingId ? __('Изменить товар') : __('Новый товар') }}</h2>
                <p class="muted" style="margin:-6px 0 14px;font-size:13px">{{ __('Основной текст — на русском, переводы внизу формы.') }}</p>
                <form wire:submit="save" class="form">
                    <div class="form-row">
                        <div>
                            <label>{{ __('Бренд') }}</label>
                            <select class="input" wire:model="form.brand_id">
                                @foreach ($brands as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
                            </select>
                        </div>
                        <div><label>{{ __('Название') }}</label><input class="input" wire:model="form.name">@error('form.name')<div class="error">{{ $message }}</div>@enderror @error('slug')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Категория') }}</label><input class="input" wire:model="form.category" placeholder="{{ __('Столы, Светильники…') }}"></div>
                        <div><label>{{ __('Цена / условия') }}</label><input class="input" wire:model="form.price_note" placeholder="{{ __('Под заказ, 6–8 недель') }}"></div>
                    </div>
                    <div><label>{{ __('Описание') }}</label><textarea class="input" style="min-height:90px" wire:model="form.description"></textarea></div>
                    @include('partials.admin.image-field', ['model' => 'form.image', 'label' => 'Фото товара'])
                    <div class="form-row">
                        <div><label>{{ __('Порядок') }}</label><input class="input" type="number" wire:model="form.sort"></div>
                        <div style="display:flex;align-items:end;padding-bottom:12px"><label class="check"><input type="checkbox" wire:model="form.is_active"> {{ __('Показывать') }}</label></div>
                    </div>
                    @include('partials.admin.translations', ['fields' => ['name' => 'Название', 'category' => 'Категория', 'description' => 'Описание', 'price_note' => 'Цена / условия'], 'long' => ['description']])
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
