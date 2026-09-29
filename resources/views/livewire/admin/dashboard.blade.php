<div>
    <div class="admin-top">
        <div>
            <div class="eyebrow">Админпанель</div>
            <h1>Здравствуйте, {{ auth()->user()->name }}</h1>
        </div>
    </div>
    <div class="kpis">
        @foreach ($kpis as [$label, $value])
            <div class="kpi"><strong>{{ $value }}</strong><span>{{ $label }}</span></div>
        @endforeach
    </div>
    <div class="grid grid-2" style="align-items:start">
        <div>
            <div class="admin-top" style="margin-bottom:12px"><h3 style="margin:0">Последние заявки</h3><a class="link-arrow" href="{{ route('admin.orders') }}" wire:navigate>Все</a></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td><strong>{{ $o->name }}</strong><div class="small muted">{{ $o->service?->title ?? 'Консультация' }}</div></td>
                            <td class="small">{{ $o->created_at->format('d.m H:i') }}</td>
                            <td><span class="status status--{{ $o->status }}">{{ $o->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td class="muted">Заявок пока нет</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="admin-top" style="margin-bottom:12px"><h3 style="margin:0">Запросы на импорт</h3><a class="link-arrow" href="{{ route('admin.imports') }}" wire:navigate>Все</a></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                    @forelse ($imports as $r)
                        <tr>
                            <td><strong>{{ $r->title }}</strong><div class="small muted">{{ $r->user->name }} · {{ $r->typeLabel() }}</div></td>
                            <td><span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td class="muted">Запросов пока нет</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
