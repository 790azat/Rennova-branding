<div>
    <div class="admin-top">
        <div>
            <a href="{{ route('admin.client-projects') }}" class="small muted" wire:navigate>← {{ __('Проекты клиентов') }}</a>
            <h1 style="margin-top:8px">{{ $project->title }}</h1>
            <div class="small muted">{{ $project->user->name }} · {{ $project->user->email }}@if($project->user->phone) · {{ $project->user->phone }}@endif</div>
        </div>
        <div class="actions">
            <a class="btn btn--ghost btn--sm" href="{{ route('cabinet.project', $project) }}" target="_blank">{{ __('Как видит клиент') }}</a>
            <button class="btn btn--danger btn--sm" wire:click="deleteProject" wire:confirm="{{ __('Удалить проект со всеми этапами и новостями?') }}">{{ __('Удалить') }}</button>
        </div>
    </div>

    <div class="grid grid-2" style="align-items:start">
        <div class="panel" style="padding:28px">
            <h3>{{ __('Этапы') }} · {{ $project->progress() }}%</h3>
            @include('partials.progress', ['value' => $project->progress()])
            <div style="margin-top:18px">
                @foreach ($stages as $id => $s)
                    <div class="stage-edit" wire:key="st-{{ $id }}" x-data="{ edit: false }">
                        <div class="stage-edit-row">
                            <select class="input stage-status stage-status--{{ $s['status'] }}" wire:change="setStageStatus({{ $id }}, $event.target.value)">
                                @foreach (\App\Models\ClientProjectStage::STATUSES as $key => $label)<option value="{{ $key }}" @selected($s['status'] === $key)>{{ __($label) }}</option>@endforeach
                            </select>
                            <strong style="flex:1">{{ $s['title'] }}</strong>
                            <button type="button" class="btn btn--ghost btn--sm" wire:click="moveStage({{ $id }}, -1)" aria-label="{{ __('Выше') }}">↑</button>
                            <button type="button" class="btn btn--ghost btn--sm" wire:click="moveStage({{ $id }}, 1)" aria-label="{{ __('Ниже') }}">↓</button>
                            <button type="button" class="btn btn--ghost btn--sm" @click="edit = !edit">{{ __('Изменить') }}</button>
                        </div>
                        <div x-show="edit" x-cloak class="form" style="margin-top:10px">
                            <div class="form-row">
                                <div><label>{{ __('Название') }}</label><input class="input" wire:model="stages.{{ $id }}.title"></div>
                                <div><label>{{ __('Срок') }}</label><input class="input" type="date" wire:model="stages.{{ $id }}.ends_on"></div>
                            </div>
                            <div><label>{{ __('Заметка для клиента') }}</label><input class="input" wire:model="stages.{{ $id }}.note"></div>
                            <div class="actions">
                                <button type="button" class="btn btn--sm" wire:click="saveStage({{ $id }})" @click="edit = false">{{ __('Сохранить') }}</button>
                                <button type="button" class="btn btn--danger btn--sm" wire:click="deleteStage({{ $id }})" wire:confirm="{{ __('Удалить этап?') }}">{{ __('Удалить') }}</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <form wire:submit="addStage" style="display:flex;gap:8px;margin-top:16px">
                <input class="input" wire:model="newStage" placeholder="{{ __('Новый этап') }}">
                <button class="btn btn--sm" type="submit">{{ __('Добавить') }}</button>
            </form>
            @error('newStage')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="grid" style="gap:24px">
            <div class="panel" style="padding:28px">
                <h3>{{ __('Новость для клиента') }}</h3>
                <form wire:submit="postUpdate" class="form">
                    <div><textarea class="input" style="min-height:90px" wire:model="updateBody" placeholder="{{ __('Что сделано, что дальше') }}"></textarea>@error('updateBody')<div class="error">{{ $message }}</div>@enderror</div>
                    @include('partials.admin.image-field', ['model' => 'updateImage', 'label' => 'Фото'])
                    <div><button class="btn btn--sm" type="submit">{{ __('Опубликовать') }}</button></div>
                </form>
                @foreach ($updates as $u)
                    <div class="update" wire:key="up-{{ $u->id }}">
                        <div class="small muted">{{ $u->created_at->format('d.m.Y H:i') }} · <a href="#" wire:click.prevent="deleteUpdate({{ $u->id }})" wire:confirm="{{ __('Удалить новость?') }}" style="color:var(--danger)">{{ __('удалить') }}</a></div>
                        <p style="white-space:pre-line;margin:6px 0 0">{{ $u->body }}</p>
                        @if ($u->image)<img src="{{ $u->image }}" alt="" loading="lazy">@endif
                    </div>
                @endforeach
            </div>

            <div class="panel" style="padding:28px">
                <h3>{{ __('О проекте') }}</h3>
                @if (session('saved'))<div class="alert">{{ session('saved') }}</div>@endif
                <form wire:submit="saveInfo" class="form">
                    <div><label>{{ __('Название') }}</label><input class="input" wire:model="info.title">@error('info.title')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="form-row">
                        <div>
                            <label>{{ __('Статус') }}</label>
                            <select class="input" wire:model="info.status">
                                @foreach (\App\Models\ClientProject::STATUSES as $key => $label)<option value="{{ $key }}">{{ __($label) }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label>{{ __('Услуга') }}</label>
                            <select class="input" wire:model="info.service_id">
                                <option value="">—</option>
                                @foreach ($services as $s)<option value="{{ $s->id }}">{{ $s->title }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div><label>{{ __('Адрес') }}</label><input class="input" wire:model="info.address"></div>
                    <div class="form-row">
                        <div><label>{{ __('Начало') }}</label><input class="input" type="date" wire:model="info.starts_on"></div>
                        <div><label>{{ __('Плановое завершение') }}</label><input class="input" type="date" wire:model="info.ends_on"></div>
                    </div>
                    <div><label>{{ __('Менеджер') }}</label><input class="input" wire:model="info.manager"></div>
                    <div><label>{{ __('Заметка для клиента') }}</label><textarea class="input" style="min-height:80px" wire:model="info.note"></textarea></div>
                    <div><button class="btn btn--sm" type="submit">{{ __('Сохранить') }}</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
