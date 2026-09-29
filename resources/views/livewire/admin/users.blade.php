<div>
    <div class="admin-top">
        <h1>Пользователи</h1>
        <input class="input" style="max-width:320px" type="search" placeholder="Имя или email" wire:model.live.debounce.400ms="search">
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Пользователь</th><th>Телефон</th><th>Обсуждений</th><th>Импорт</th><th>Роль</th><th>С нами с</th><th></th></tr></thead>
            <tbody>
            @foreach ($users as $u)
                <tr wire:key="u-{{ $u->id }}">
                    <td><strong>{{ $u->name }}</strong><div class="small muted">{{ $u->email }}</div></td>
                    <td class="small">{{ $u->phone ?? '—' }}</td>
                    <td>{{ $u->discussions_count }}</td>
                    <td>{{ $u->import_requests_count }}</td>
                    <td><span class="status {{ $u->isAdmin() ? 'status--done' : '' }}">{{ $u->isAdmin() ? 'Админ' : 'Пользователь' }}</span></td>
                    <td class="small">{{ $u->created_at->format('d.m.Y') }}</td>
                    <td class="actions">
                        @if ($u->id !== auth()->id())
                            <button class="btn btn--ghost btn--sm" wire:click="toggleAdmin({{ $u->id }})" wire:confirm="Изменить роль пользователя?">{{ $u->isAdmin() ? 'Снять админа' : 'Сделать админом' }}</button>
                            <button class="btn btn--danger btn--sm" wire:click="delete({{ $u->id }})" wire:confirm="Удалить пользователя и все его данные?">Удалить</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links('partials.pagination') }}</div>
</div>
