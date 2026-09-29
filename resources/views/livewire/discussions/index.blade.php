<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>Главная</a> / Обсуждения</div>
            <div class="eyebrow">Сообщество</div>
            <h1>Обсуждения дизайна, архитектуры и ремонта</h1>
            <p class="lead">Задавайте вопросы, делитесь идеями и получайте ответы специалистов Rennova.</p>
        </div>
        <x-landmark name="opera" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            <div class="toolbar">
                <div class="filters" style="margin:0">
                    <button class="chip {{ $category === '' ? 'active' : '' }}" wire:click="$set('category', '')">Все</button>
                    @foreach (\App\Models\Discussion::CATEGORIES as $key => $label)
                        <button class="chip {{ $category === $key ? 'active' : '' }}" wire:click="$set('category', '{{ $key }}')">{{ $label }}</button>
                    @endforeach
                </div>
                <div style="display:flex;gap:12px;flex:1;justify-content:flex-end;flex-wrap:wrap">
                    <input class="input" type="search" placeholder="Поиск по обсуждениям" wire:model.live.debounce.400ms="search">
                    <button class="btn" wire:click="startCreating">Новая тема</button>
                </div>
            </div>

            @if ($creating)
                <div class="panel" style="margin-bottom:32px">
                    <h3>Новая тема</h3>
                    <form wire:submit="create" class="form">
                        <div class="form-row">
                            <div>
                                <label>Раздел</label>
                                <select class="input" wire:model="newCategory">
                                    @foreach (\App\Models\Discussion::CATEGORIES as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Заголовок</label>
                                <input class="input" wire:model="newTitle" placeholder="Коротко о главном">
                                @error('newTitle')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div>
                            <label>Текст</label>
                            <textarea class="input" wire:model="newBody" placeholder="Опишите проект, идею или вопрос"></textarea>
                            @error('newBody')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="actions">
                            <button class="btn" type="submit">Опубликовать</button>
                            <button class="btn btn--ghost" type="button" wire:click="$set('creating', false)">Отмена</button>
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
                                @if ($d->is_pinned)<span class="pin">Закреплено</span>@endif
                                <a href="{{ route('discussions.show', $d) }}" wire:navigate>{{ $d->title }}</a>
                            </h3>
                            <div class="small muted">
                                {{ $d->categoryLabel() }} · {{ $d->user->name }} · {{ $d->last_activity_at?->diffForHumans() }}
                                @if ($d->is_closed) · закрыто @endif
                            </div>
                        </div>
                        <div class="stats"><strong>{{ $d->replies_count }}</strong>ответов</div>
                    </div>
                @empty
                    <div class="empty">Обсуждений пока нет. Начните первое!</div>
                @endforelse
            </div>
            <div class="pagination">{{ $discussions->links('partials.pagination') }}</div>
        </div>
    </section>
</div>
