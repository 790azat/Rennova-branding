<div>
    <div class="admin-top">
        <h1>{{ __('Блог') }}</h1>
        <button class="btn" wire:click="create">{{ __('Новая статья') }}</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Статья') }}</th><th>{{ __('Раздел') }}</th><th>{{ __('Публикация') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($posts as $p)
                <tr wire:key="apo-{{ $p->id }}">
                    <td><strong>{{ $p->title }}</strong><div class="small muted">/blog/{{ $p->slug }}</div></td>
                    <td class="small">{{ $p->categoryLabel() }}</td>
                    <td class="small">
                        @if ($p->isPublished()){{ $p->published_at->format('d.m.Y H:i') }}
                        @elseif ($p->published_at)<span class="status status--quoted">{{ __('Запланирована') }}</span> {{ $p->published_at->format('d.m.Y H:i') }}
                        @else<span class="status">{{ __('Черновик') }}</span>@endif
                    </td>
                    <td class="actions">
                        <a class="btn btn--ghost btn--sm" href="{{ route('blog.show', $p) }}" target="_blank">{{ __('Открыть') }}</a>
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $p->id }})">{{ __('Изменить') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Удалить статью?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">{{ __('Статей пока нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal" style="max-width:900px">
                <h2>{{ $editingId ? __('Изменить статью') : __('Новая статья') }}</h2>
                <p class="muted" style="margin:-6px 0 14px;font-size:13px">{{ __('Текст пишется в Markdown: ## подзаголовок, **жирный**, - список, [ссылка](https://…).') }}</p>
                <form wire:submit="save" class="form">
                    <div class="form-row">
                        <div><label>{{ __('Заголовок') }}</label><input class="input" wire:model="form.title">@error('form.title')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Адрес (slug)') }}</label><input class="input" wire:model="form.slug" placeholder="{{ __('создастся из названия') }}">@error('form.slug')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label>{{ __('Раздел') }}</label>
                            <select class="input" wire:model="form.category">
                                @foreach (\App\Models\Post::CATEGORIES as $key => $label)<option value="{{ $key }}">{{ __($label) }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label>{{ __('Здание-мотив') }}</label>
                            <select class="input" wire:model="form.landmark">
                                @foreach (config('rennova.landmarks') as $key => [$name])<option value="{{ $key }}">{{ __($name) }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div><label>{{ __('Анонс (для списка и поисковиков)') }}</label><textarea class="input" style="min-height:70px" wire:model="form.excerpt"></textarea></div>
                    <div><label>{{ __('Текст') }}</label><textarea class="input" style="min-height:320px;font-family:ui-monospace,monospace;font-size:.88rem" wire:model="form.body"></textarea>@error('form.body')<div class="error">{{ $message }}</div>@enderror</div>
                    @include('partials.admin.image-field', ['model' => 'form.cover_image', 'label' => 'Обложка'])
                    <div class="form-row">
                        <div style="display:flex;align-items:end;padding-bottom:12px"><label class="check"><input type="checkbox" wire:model.live="form.is_published"> {{ __('Опубликовать') }}</label></div>
                        @if ($form['is_published'])
                            <div><label>{{ __('Дата публикации') }}</label><input class="input" type="datetime-local" wire:model="form.published_at"></div>
                        @endif
                    </div>
                    @include('partials.admin.translations', ['fields' => ['title' => 'Заголовок', 'excerpt' => 'Анонс (для списка и поисковиков)', 'body' => 'Текст'], 'long' => ['excerpt', 'body']])
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
