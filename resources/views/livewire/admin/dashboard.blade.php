<div>
    <div class="admin-top">
        <div>
            <div class="eyebrow">{{ __('Админпанель') }}</div>
            <h1>{{ __('Здравствуйте, :name', ['name' => auth()->user()->name]) }}</h1>
        </div>
    </div>
    <div class="kpis">
        @foreach ($kpis as [$label, $value])
            <div class="kpi"><strong>{{ $value }}</strong><span>{{ __($label) }}</span></div>
        @endforeach
    </div>
    <div class="grid grid-2" style="align-items:start">
        <div>
            <div class="admin-top" style="margin-bottom:12px"><h3 style="margin:0">{{ __('Последние заявки') }}</h3><a class="link-arrow" href="{{ route('admin.orders') }}" wire:navigate>{{ __('Все') }}</a></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td><strong>{{ $o->name }}</strong><div class="small muted">{{ $o->service?->tr('title') ?? __('Консультация') }}</div></td>
                            <td class="small">{{ $o->created_at->format('d.m H:i') }}</td>
                            <td><span class="status status--{{ $o->status }}">{{ $o->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td class="muted">{{ __('Заявок пока нет') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="admin-top" style="margin-bottom:12px"><h3 style="margin:0">{{ __('Запросы на импорт') }}</h3><a class="link-arrow" href="{{ route('admin.imports') }}" wire:navigate>{{ __('Все') }}</a></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                    @forelse ($imports as $r)
                        <tr>
                            <td><strong>{{ $r->title }}</strong><div class="small muted">{{ $r->user->name }} · {{ $r->typeLabel() }}</div></td>
                            <td><span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td class="muted">{{ __('Запросов пока нет') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
