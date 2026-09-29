<div>
    <div class="admin-top"><h1>{{ __('Записи на консультацию') }}</h1></div>
    <div class="filters">
        <button class="chip {{ $when === 'upcoming' ? 'active' : '' }}" wire:click="$set('when', 'upcoming')">{{ __('Предстоящие') }}</button>
        <button class="chip {{ $when === 'past' ? 'active' : '' }}" wire:click="$set('when', 'past')">{{ __('Прошедшие') }}</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Когда') }}</th><th>{{ __('Клиент') }}</th><th>{{ __('Формат') }}</th><th>{{ __('Тема') }}</th><th>{{ __('Комментарий') }}</th><th>{{ __('Статус') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($appointments as $a)
                <tr wire:key="apt-{{ $a->id }}">
                    <td><strong>{{ $a->date->translatedFormat('j M, D') }}</strong><div>{{ $a->time }}</div></td>
                    <td><strong>{{ $a->name }}</strong><div class="small"><a href="tel:{{ $a->phone }}">{{ $a->phone }}</a></div>@if($a->email)<div class="small muted">{{ $a->email }}</div>@endif</td>
                    <td class="small">{{ $a->formatLabel() }}</td>
                    <td class="small">{{ $a->service?->tr('title') ?? '—' }}</td>
                    <td class="small" style="max-width:260px;white-space:pre-line">{{ $a->comment }}</td>
                    <td>
                        <select class="input" wire:change="setStatus({{ $a->id }}, $event.target.value)">
                            @foreach (\App\Models\Appointment::STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected($a->status === $key)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><button class="btn btn--danger btn--sm" wire:click="delete({{ $a->id }})" wire:confirm="{{ __('Удалить запись?') }}">{{ __('Удалить') }}</button></td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted">{{ __('Записей нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $appointments->links('partials.pagination') }}</div>
</div>
