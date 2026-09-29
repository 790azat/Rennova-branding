<div>
    <div class="admin-top"><h1>{{ __('Обсуждения') }}</h1></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Тема') }}</th><th>{{ __('Автор') }}</th><th>{{ __('Ответов') }}</th><th>{{ __('Активность') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($discussions as $d)
                <tr wire:key="ad-{{ $d->id }}">
                    <td>
                        @if ($d->is_pinned)<span class="pin">{{ __('Закреплено') }}</span>@endif
                        <a href="{{ route('discussions.show', $d) }}" target="_blank"><strong>{{ $d->title }}</strong></a>
                        <div class="small muted">{{ $d->categoryLabel() }} @if($d->is_closed) · {{ __('закрыто') }} @endif</div>
                    </td>
                    <td class="small">{{ $d->user->name }}</td>
                    <td>{{ $d->replies_count }}</td>
                    <td class="small">{{ $d->last_activity_at?->format('d.m.Y H:i') }}</td>
                    <td class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="toggle({{ $d->id }}, 'is_pinned')">{{ $d->is_pinned ? __('Открепить') : __('Закрепить') }}</button>
                        <button class="btn btn--ghost btn--sm" wire:click="toggle({{ $d->id }}, 'is_closed')">{{ $d->is_closed ? __('Открыть') : __('Закрыть') }}</button>
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $d->id }})" wire:confirm="{{ __('Удалить обсуждение со всеми ответами?') }}">{{ __('Удалить') }}</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">{{ __('Обсуждений нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $discussions->links('partials.pagination') }}</div>
</div>
