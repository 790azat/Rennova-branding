<div>
    <div class="admin-top"><h1>{{ __('Заявки на услуги') }}</h1></div>
    <div class="filters">
        <button class="chip {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">{{ __('Все') }}</button>
        @foreach (\App\Models\ServiceOrder::STATUSES as $key => $label)
            <button class="chip {{ $status === $key ? 'active' : '' }}" wire:click="$set('status', '{{ $key }}')">{{ __($label) }}</button>
        @endforeach
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('Дата') }}</th><th>{{ __('Клиент') }}</th><th>{{ __('Услуга') }}</th><th>{{ __('Сообщение') }}</th><th>{{ __('Статус') }}</th><th></th></tr></thead>
            <tbody>
            @forelse ($orders as $o)
                <tr wire:key="o-{{ $o->id }}">
                    <td class="small">{{ $o->created_at->format('d.m.Y H:i') }}</td>
                    <td><strong>{{ $o->name }}</strong><div class="small"><a href="tel:{{ $o->phone }}">{{ $o->phone }}</a></div>@if($o->email)<div class="small muted">{{ $o->email }}</div>@endif</td>
                    <td>{{ $o->service?->tr('title') ?? __('Консультация') }}</td>
                    <td class="small" style="max-width:320px;white-space:pre-line">{{ $o->message }}</td>
                    <td>
                        <select class="input" wire:change="setStatus({{ $o->id }}, $event.target.value)">
                            @foreach (\App\Models\ServiceOrder::STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected($o->status === $key)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><button class="btn btn--danger btn--sm" wire:click="delete({{ $o->id }})" wire:confirm="{{ __('Удалить заявку?') }}">{{ __('Удалить') }}</button></td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">{{ __('Заявок нет') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $orders->links('partials.pagination') }}</div>
</div>
