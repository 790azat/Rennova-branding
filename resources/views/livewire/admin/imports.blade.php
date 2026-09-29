<div>
    <div class="admin-top"><h1>{{ __('Запросы на импорт') }}</h1></div>
    <div class="filters">
        <button class="chip {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">{{ __('Все') }}</button>
        @foreach (\App\Models\ImportRequest::STATUSES as $key => $label)
            <button class="chip {{ $status === $key ? 'active' : '' }}" wire:click="$set('status', '{{ $key }}')">{{ __($label) }}</button>
        @endforeach
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Дата') }}</th><th>{{ __('Пользователь') }}</th><th>{{ __('Запрос') }}</th><th>{{ __('Кол-во / бюджет') }}</th><th>{{ __('Статус') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($requests as $r)
                <tr wire:key="ir-{{ $r->id }}">
                    <td class="small">{{ $r->created_at->format('d.m.Y') }}</td>
                    <td><strong>{{ $r->user->name }}</strong><div class="small muted">{{ $r->user->email }}</div>@if($r->user->phone)<div class="small">{{ $r->user->phone }}</div>@endif</td>
                    <td style="max-width:360px">
                        <strong>{{ $r->title }}</strong>
                        <div class="small muted">{{ $r->typeLabel() }}@if($r->preferred_brand) · {{ $r->preferred_brand }}@endif</div>
                        <div class="small" style="white-space:pre-line;margin-top:6px">{{ $r->description }}</div>
                        @if ($r->admin_note)<div class="small" style="margin-top:6px;color:var(--olive)">{{ __('Ответ:') }} {{ $r->admin_note }}</div>@endif
                    </td>
                    <td class="small">{{ $r->quantity ?? '—' }}<br>{{ $r->budget ? number_format($r->budget, 0, ',', ' ').' ֏' : '—' }}</td>
                    <td><span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $r->id }})">{{ __('Ответить') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $r->id }})" wire:confirm="{{ __('Удалить запрос?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">{{ __('Запросов нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $requests->links('partials.pagination') }}</div>

    @if ($editingId)
        <div class="modal-bg" wire:click.self="$set('editingId', null)">
            <div class="modal">
                <h2>{{ __('Ответ на запрос') }}</h2>
                <form wire:submit="save" class="form">
                    <div>
                        <label>{{ __('Статус') }}</label>
                        <select class="input" wire:model="editStatus">
                            @foreach (\App\Models\ImportRequest::STATUSES as $key => $label)
                                <option value="{{ $key }}">{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>{{ __('Комментарий для клиента') }}</label>
                        <textarea class="input" wire:model="note" placeholder="{{ __('Расчёт, сроки, условия') }}"></textarea>
                    </div>
                    <div class="actions">
                        <button class="btn" type="submit">{{ __('Сохранить') }}</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('editingId', null)">{{ __('Отмена') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
