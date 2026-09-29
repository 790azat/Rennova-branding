<div>
    <div class="admin-top"><h1>Обсуждения</h1></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Тема</th><th>Автор</th><th>Ответов</th><th>Активность</th><th></th></tr></thead>
            <tbody>
            @forelse ($discussions as $d)
                <tr wire:key="ad-{{ $d->id }}">
                    <td>
                        @if ($d->is_pinned)<span class="pin">Закреплено</span>@endif
                        <a href="{{ route('discussions.show', $d) }}" target="_blank"><strong>{{ $d->title }}</strong></a>
                        <div class="small muted">{{ $d->categoryLabel() }} @if($d->is_closed) · закрыто @endif</div>
                    </td>
                    <td class="small">{{ $d->user->name }}</td>
                    <td>{{ $d->replies_count }}</td>
                    <td class="small">{{ $d->last_activity_at?->format('d.m.Y H:i') }}</td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="toggle({{ $d->id }}, 'is_pinned')">{{ $d->is_pinned ? 'Открепить' : 'Закрепить' }}</button>
                        <button class="btn btn--ghost btn--sm" wire:click="toggle({{ $d->id }}, 'is_closed')">{{ $d->is_closed ? 'Открыть' : 'Закрыть' }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $d->id }})" wire:confirm="Удалить обсуждение со всеми ответами?">Удалить</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Обсуждений нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $discussions->links('partials.pagination') }}</div>
</div>
