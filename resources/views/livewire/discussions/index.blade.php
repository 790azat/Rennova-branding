<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> {{ __('/ Обсуждения') }}</div>
            <div class="eyebrow">{{ __('Сообщество') }}</div>
            <h1>{{ __('Обсуждения дизайна, архитектуры и ремонта') }}</h1>
            <p class="lead">{{ __('Задавайте вопросы, делитесь идеями и получайте ответы специалистов Rennova.') }}</p>
        </div>
        <x-landmark name="opera" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            <div class="toolbar">
                <div class="filters" style="margin:0">
                    <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">{{ __('Все') }}</button>
                    @foreach (\App\Models\Discussion::CATEGORIES as $key => $label)
                        <button class="chip {{ $category === $key ? 'active' : '' }}" wire:click="$set('category', '{{ $key }}')">{{ __($label) }}</button>
                    @endforeach
                </div>
                <div style="display:flex;gap:12px;flex:1;justify-content:flex-end;flex-wrap:wrap">
                    <input class="input" type="search" placeholder="{{ __('Поиск по обсуждениям') }}" wire:model.live.debounce.400ms="search">
                    <button class="btn" wire:click="startCreating">{{ __('Новая тема') }}</button>
                </div>
            </div>

            @if ($creating)
                <div class="panel" style="margin-bottom:32px">
                    <h3>{{ __('Новая тема') }}</h3>
                    <form wire:submit="create" class="form">
                        <div class="form-row">
                            <div>
                                <label>{{ __('Раздел') }}</label>
                                <select class="input" wire:model="newCategory">
                                    @foreach (\App\Models\Discussion::CATEGORIES as $key => $label)
                                        <option value="{{ $key }}">{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>{{ __('Заголовок') }}</label>
                                <input class="input" wire:model="newTitle" placeholder="{{ __('Коротко о главном') }}">
                                @error('newTitle')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div>
                            <label>{{ __('Текст') }}</label>
                            <textarea class="input" wire:model="newBody" placeholder="{{ __('Опишите проект, идею или вопрос') }}"></textarea>
                            @error('newBody')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="actions">
                            <button class="btn" type="submit">{{ __('Опубликовать') }}</button>
                            <button class="btn btn--ghost" type="button" wire:click="$set('creating', false)">{{ __('Отмена') }}</button>
                        </div>
                    </form>
                </div>
            @endif

            <div>
                @forelse ($discussions as $d)
                    <div class="topic" wire:key="d-{{ $d->id }}">
                        <div class="avatar">{{ $d->user->initials() }}</div>
                        <div>
                            <h3>
                                @if ($d->is_pinned)<span class="pin">{{ __('Закреплено') }}</span>@endif
                                <a href="{{ route('discussions.show', $d) }}" wire:navigate>{{ $d->title }}</a>
                            </h3>
                            <div class="small muted">
                                {{ $d->categoryLabel() }} · {{ $d->user->name }} · {{ $d->last_activity_at?->diffForHumans() }}
                                @if ($d->is_closed) · {{ __('закрыто') }} @endif
                            </div>
                        </div>
                        <div class="stats"><strong>{{ $d->replies_count }}</strong>{{ __('ответов') }}</div>
                    </div>
                @empty
                    <div class="empty">{{ __('Обсуждений пока нет. Начните первое!') }}</div>
                @endforelse
            </div>
            <div class="pagination">{{ $discussions->links('partials.pagination') }}</div>
        </div>
    </section>
</div>
