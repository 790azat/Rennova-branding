<div>
    <div class="admin-top"><h1>Запросы на импорт</h1></div>
    <div class="filters">
        <button class="chip {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">Все</button>
        @foreach (\App\Models\ImportRequest::STATUSES as $key => $label)
            <button class="chip {{ $status === $key ? 'active' : '' }}" wire:click="$set('status', '{{ $key }}')">{{ $label }}</button>
        @endforeach
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Дата</th><th>Пользователь</th><th>Запрос</th><th>Кол-во / бюджет</th><th>Статус</th><th></th></tr></thead>
            <tbody>
            @forelse ($requests as $r)
                <tr wire:key="ir-{{ $r->id }}">
                    <td class="small">{{ $r->created_at->format('d.m.Y') }}</td>
                    <td><strong>{{ $r->user->name }}</strong><div class="small muted">{{ $r->user->email }}</div>@if($r->user->phone)<div class="small">{{ $r->user->phone }}</div>@endif</td>
                    <td style="max-width:360px">
                        <strong>{{ $r->title }}</strong>
                        <div class="small muted">{{ $r->typeLabel() }}@if($r->preferred_brand) · {{ $r->preferred_brand }}@endif</div>
                        <div class="small" style="white-space:pre-line;margin-top:6px">{{ $r->description }}</div>
                        @if ($r->admin_note)<div class="small" style="margin-top:6px;color:var(--olive)">Ответ: {{ $r->admin_note }}</div>@endif
                    </td>
                    <td class="small">{{ $r->quantity ?? '—' }}<br>{{ $r->budget ? number_format($r->budget, 0, ',', ' ').' ֏' : '—' }}</td>
                    <td><span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span></td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="edit({{ $r->id }})">Ответить</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $r->id }})" wire:confirm="Удалить запрос?">Удалить</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Запросов нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $requests->links('partials.pagination') }}</div>

    @if ($editingId)
        <div class="modal-bg" wire:click.self="$set('editingId', null)">
            <div class="modal">
                <h2>Ответ на запрос</h2>
                <form wire:submit="save" class="form">
                    <div>
                        <label>Статус</label>
                        <select class="input" wire:model="editStatus">
                            @foreach (\App\Models\ImportRequest::STATUSES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Комментарий для клиента</label>
                        <textarea class="input" wire:model="note" placeholder="Расчёт, сроки, условия"></textarea>
                    </div>
                    <div class="actions">
                        <button class="btn" type="submit">Сохранить</button>
                        <button class="btn btn--ghost" type="button" wire:click="$set('editingId', null)">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
