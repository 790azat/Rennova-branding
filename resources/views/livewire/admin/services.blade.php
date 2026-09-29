<div>
    <div class="admin-top">
        <h1>{{ __('Услуги') }}</h1>
        <button class="btn" wire:click="create">{{ __('Добавить услугу') }}</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>{{ __('Услуга') }}</th><th>{{ __('Категория') }}</th><th>{{ __('Цена') }}</th><th>{{ __('Статус') }}</th><th></th></tr></thead>
            <tbody>
            @foreach ($services as $s)
                <tr wire:key="as-{{ $s->id }}">
                    <td class="small muted">{{ $s->sort }}</td>
                    <td><div style="display:flex;gap:14px;align-items:center">
                        <x-landmark :name="$s->landmark ?? 'eiffel'" style="height:44px;width:auto;color:var(--olive)" />
                        <div><strong>{{ $s->tr('title') }}</strong><div class="small muted">/services/{{ $s->slug }}</div></div>
                    </div></td>
                    <td>{{ $s->categoryLabel() }}</td>
                    <td class="small">{{ $s->priceLabel() ?? '—' }}</td>
                    <td><span class="status {{ $s->is_active ? 'status--done' : 'status--cancelled' }}">{{ $s->is_active ? __('Активна') : __('Скрыта') }}</span></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $s->id }})">{{ __('Изменить') }}</button>
                        <button class="btn btn--ghost btn--sm" wire:click="toggle({{ $s->id }})">{{ $s->is_active ? __('Скрыть') : __('Показать') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $s->id }})" wire:confirm="{{ __('Удалить услугу?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ $editingId ? __('Изменить услугу') : __('Новая услуга') }}</h2>
                <p class="muted" style="margin:-6px 0 14px;font-size:13px">{{ __('Основной текст — на русском, переводы внизу формы.') }}</p>
                <form wire:submit="save" class="form">
                    <div class="form-row">
                        <div><label>{{ __('Название') }}</label><input class="input" wire:model="form.title">@error('form.title')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Адрес (slug)') }}</label><input class="input" wire:model="form.slug" placeholder="{{ __('создастся из названия') }}">@error('form.slug')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label>{{ __('Категория') }}</label>
                            <select class="input" wire:model="form.category">
                                @foreach (\App\Models\Service::CATEGORIES as $key => $label)<option value="{{ $key }}">{{ __($label) }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label>{{ __('Здание-мотив') }}</label>
                            <select class="input" wire:model="form.landmark">
                                @foreach (config('rennova.landmarks') as $key => [$name])<option value="{{ $key }}">{{ __($name) }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div><label>{{ __('Краткое описание') }}</label><textarea class="input" style="min-height:80px" wire:model="form.excerpt"></textarea></div>
                    <div><label>{{ __('Полное описание') }}</label><textarea class="input" wire:model="form.description"></textarea></div>
                    <div><label>{{ __('Что входит (по одному пункту в строке)') }}</label><textarea class="input" wire:model="form.features"></textarea></div>
                    <div class="form-row">
                        <div><label>{{ __('Цена от, ֏') }}</label><input class="input" type="number" wire:model="form.price_from">@error('form.price_from')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Единица') }}</label><input class="input" wire:model="form.price_unit" placeholder="{{ __('м², месяц, объект') }}"></div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Порядок') }}</label><input class="input" type="number" wire:model="form.sort"></div>
                        <div style="display:flex;gap:20px;align-items:end;padding-bottom:12px">
                            <label class="check"><input type="checkbox" wire:model="form.is_active"> {{ __('Активна') }}</label>
                            <label class="check"><input type="checkbox" wire:model="form.is_bundle"> {{ __('Комплексная') }}</label>
                        </div>
                    </div>
                    @include('partials.admin.translations', ['fields' => ['title' => 'Название', 'excerpt' => 'Краткое описание', 'description' => 'Полное описание', 'features' => 'Что входит (по одному пункту в строке)'], 'long' => ['excerpt', 'description', 'features']])
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
