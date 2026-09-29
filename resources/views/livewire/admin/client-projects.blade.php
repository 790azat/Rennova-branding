<div>
    <div class="admin-top">
        <h1>{{ __('Проекты клиентов') }}</h1>
        <button class="btn" wire:click="create">{{ __('Новый проект') }}</button>
    </div>
    <p class="muted" style="margin-top:-16px">{{ __('Клиент видит свой проект, этапы и новости в личном кабинете, в разделе «Мои проекты».') }}</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Проект') }}</th><th>{{ __('Клиент') }}</th><th>{{ __('Статус') }}</th><th>{{ __('Готовность') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($projects as $p)
                <tr wire:key="acp-{{ $p->id }}">
                    <td><strong>{{ $p->title }}</strong><div class="small muted">{{ $p->service?->title }}@if($p->address) · {{ $p->address }}@endif</div></td>
                    <td class="small">{{ $p->user->name }}<div class="muted">{{ $p->user->email }}</div></td>
                    <td><span class="status status--{{ $p->status }}">{{ $p->statusLabel() }}</span></td>
                    <td style="min-width:140px">@include('partials.progress', ['value' => $p->progress()])<span class="small muted">{{ $p->progress() }}%</span></td>
                    <td><a class="btn btn--ghost btn--sm" href="{{ route('admin.client-projects.show', $p) }}" wire:navigate>{{ __('Открыть') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">{{ __('Проектов пока нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($open)
        <div class="modal-bg" wire:click.self="$set('open', false)">
            <div class="modal">
                <h2>{{ __('Новый проект') }}</h2>
                <p class="muted" style="margin:-6px 0 14px;font-size:13px">{{ __('Этапы создадутся по шаблону выбранной услуги, их можно будет изменить.') }}</p>
                <form wire:submit="save" class="form">
                    <div>
                        <label>{{ __('Клиент') }}</label>
                        <select class="input" wire:model="form.user_id">
                            <option value="">—</option>
                            @foreach ($users as $u)<option value="{{ $u->id }}">{{ $u->name }} · {{ $u->email }}</option>@endforeach
                        </select>
                        @error('form.user_id')<div class="error">{{ $message }}</div>@enderror
                        <div class="small muted" style="margin-top:6px">{{ __('Клиент должен быть зарегистрирован на сайте.') }}</div>
                    </div>
                    <div class="form-row">
                        <div><label>{{ __('Название') }}</label><input class="input" wire:model="form.title" placeholder="{{ __('Квартира на Сарьяна, 85 м²') }}">@error('form.title')<div class="error">{{ $message }}</div>@enderror</div>
                        <div>
                            <label>{{ __('Услуга') }}</label>
                            <select class="input" wire:model="form.service_id">
                                <option value="">—</option>
                                @foreach ($services as $s)<option value="{{ $s->id }}">{{ $s->title }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div><label>{{ __('Адрес') }}</label><input class="input" wire:model="form.address"></div>
                    <div class="form-row">
                        <div><label>{{ __('Начало') }}</label><input class="input" type="date" wire:model="form.starts_on"></div>
                        <div><label>{{ __('Плановое завершение') }}</label><input class="input" type="date" wire:model="form.ends_on">@error('form.ends_on')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div><label>{{ __('Менеджер') }}</label><input class="input" wire:model="form.manager"></div>
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Создать') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('open', false)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
