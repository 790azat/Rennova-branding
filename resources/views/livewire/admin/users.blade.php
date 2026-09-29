<div>
    <div class="admin-top">
        <h1>{{ __('Пользователи') }}</h1>
        <input class="input" style="max-width:320px" type="search" placeholder="{{ __('Имя или email') }}" wire:model.live.debounce.400ms="search">
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Пользователь') }}</th><th>{{ __('Телефон') }}</th><th>{{ __('Обсуждений') }}</th><th>{{ __('Импорт') }}</th><th>{{ __('Роль') }}</th><th>{{ __('С нами с') }}</th><th></th></tr></thead>
            <tbody>
            @foreach ($users as $u)
                <tr wire:key="u-{{ $u->id }}">
                    <td><strong>{{ $u->name }}</strong><div class="small muted">{{ $u->email }}</div></td>
                    <td class="small">{{ $u->phone ?? '—' }}</td>
                    <td>{{ $u->discussions_count }}</td>
                    <td>{{ $u->import_requests_count }}</td>
                    <td><span class="status {{ $u->isAdmin() ? 'status--done' : '' }}">{{ $u->isAdmin() ? __('Админ') : __('Пользователь') }}</span></td>
                    <td class="small">{{ $u->created_at->format('d.m.Y') }}</td>
                    <td class="actions">
                        @if ($u->id !== auth()->id())
                            <button class="btn btn--ghost btn--sm" wire:click="toggleAdmin({{ $u->id }})" wire:confirm="{{ __('Изменить роль пользователя?') }}">{{ $u->isAdmin() ? __('Снять админа') : __('Сделать админом') }}</button>
                            <button class="btn btn--danger btn--sm" wire:click="delete({{ $u->id }})" wire:confirm="{{ __('Удалить пользователя и все его данные?') }}">{{ __('Удалить') }}</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links('partials.pagination') }}</div>
</div>
