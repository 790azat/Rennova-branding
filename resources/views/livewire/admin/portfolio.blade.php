<div>
    <div class="admin-top">
        <h1>{{ __('Портфолио') }}</h1>
        <button class="btn" wire:click="create">{{ __('Добавить проект') }}</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th></th><th>{{ __('Проект') }}</th><th>{{ __('Категория') }}</th><th>{{ __('До / после') }}</th><th>{{ __('На главной') }}</th><th>{{ __('Опубликован') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($projects as $p)
                <tr wire:key="ap-{{ $p->id }}">
                    <td>@if($p->coverUrl())<img src="{{ $p->coverUrl() }}" alt="" class="thumb">@endif</td>
                    <td><strong>{{ $p->title }}</strong><div class="small muted">{{ $p->location }}@if($p->year) · {{ $p->year }}@endif</div></td>
                    <td class="small">{{ $p->categoryLabel() }}</td>
                    <td class="small">{{ $p->hasBeforeAfter() ? '✓' : '—' }}</td>
                    <td><label class="check"><input type="checkbox" @checked($p->is_featured) wire:click="toggle({{ $p->id }}, 'is_featured')"></label></td>
                    <td><label class="check"><input type="checkbox" @checked($p->is_published) wire:click="toggle({{ $p->id }}, 'is_published')"></label></td>
                    <td class="actions">
                        <a class="btn btn--ghost btn--sm" href="{{ route('portfolio.show', $p) }}" target="_blank">{{ __('Открыть') }}</a>
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $p->id }})">{{ __('Изменить') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Удалить проект?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted">{{ __('Проектов пока нет. Добавьте первый: фото «до» и «после» покажут слайдер сравнения.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ $editingId ? __('Изменить проект') : __('Новый проект') }}</h2>
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
                            <label>{{ __('Услуга') }}</label>
                            <select class="input" wire:model="form.service_id">
                                <option value="">—</option>
                                @foreach ($services as $s)<option value="{{ $s->id }}">{{ $s->title }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Где') }}</label><input class="input" wire:model="form.location" placeholder="{{ __('Ереван, Кентрон') }}"></div>
                        <div><label>{{ __('Срок') }}</label><input class="input" wire:model="form.duration" placeholder="{{ __('3 месяца') }}"></div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Площадь, м²') }}</label><input class="input" type="number" wire:model="form.area">@error('form.area')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Год') }}</label><input class="input" type="number" wire:model="form.year">@error('form.year')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div><label>{{ __('Кратко') }}</label><textarea class="input" style="min-height:70px" wire:model="form.summary"></textarea></div>
                    <div><label>{{ __('Описание') }}</label><textarea class="input" wire:model="form.description"></textarea></div>
                    <div class="form-row">
                        @include('partials.admin.image-field', ['model' => 'form.before_image', 'label' => 'Фото «до»'])
                        @include('partials.admin.image-field', ['model' => 'form.after_image', 'label' => 'Фото «после»'])
                    </div>
                    @include('partials.admin.image-field', ['model' => 'form.cover_image', 'label' => 'Обложка (если пусто, берём фото «после»)'])
                    @include('partials.admin.image-field', ['model' => 'form.gallery', 'label' => 'Галерея', 'multiple' => true])
                    <div class="form-row">
                        <div>
                            <label>{{ __('Здание-мотив') }}</label>
                            <select class="input" wire:model="form.landmark">
                                @foreach (config('rennova.landmarks') as $key => [$name])<option value="{{ $key }}">{{ __($name) }}</option>@endforeach
                            </select>
                        </div>
                        <div><label>{{ __('Порядок') }}</label><input class="input" type="number" wire:model="form.sort"></div>
                    </div>
                    <div style="display:flex;gap:20px;flex-wrap:wrap">
                        <label class="check"><input type="checkbox" wire:model="form.is_published"> {{ __('Опубликован') }}</label>
                        <label class="check"><input type="checkbox" wire:model="form.is_featured"> {{ __('На главной') }}</label>
                    </div>
                    @include('partials.admin.translations', ['fields' => ['title' => 'Название', 'summary' => 'Кратко', 'description' => 'Описание', 'location' => 'Где', 'duration' => 'Срок'], 'long' => ['summary', 'description']])
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
