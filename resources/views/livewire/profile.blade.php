<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="eyebrow">Личный кабинет</div>
            <h1>{{ $name }}</h1>
            <p class="lead">Ваши данные, заявки на услуги и обсуждения.</p>
        </div>
        <x-landmark name="bigben" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            @if (session('saved'))<div class="alert">{{ session('saved') }}</div>@endif
            <div class="grid grid-2" style="align-items:start">
                <div class="panel">
                    <h3>Данные профиля</h3>
                    <form wire:submit="save" class="form">
                        <div><label>Имя</label><input class="input" wire:model="name">@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Email</label><input class="input" type="email" wire:model="email">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Телефон</label><input class="input" wire:model="phone">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><button class="btn" type="submit">Сохранить</button></div>
                    </form>
                </div>
                <div class="panel">
                    <h3>Смена пароля</h3>
                    <form wire:submit="changePassword" class="form">
                        <div><label>Текущий пароль</label><input class="input" type="password" wire:model="current_password">@error('current_password')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Новый пароль</label><input class="input" type="password" wire:model="password">@error('password')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Повторите пароль</label><input class="input" type="password" wire:model="password_confirmation"></div>
                        <div><button class="btn" type="submit">Обновить пароль</button></div>
                    </form>
                </div>
            </div>

            <h2 style="margin-top:64px">Мои заявки на услуги</h2>
            @if ($orders->isEmpty())
                <div class="empty">Заявок пока нет. <a href="{{ route('services.index') }}" class="link-arrow" wire:navigate>Выбрать услугу</a></div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Дата</th><th>Услуга</th><th>Сообщение</th><th>Статус</th></tr></thead>
                        <tbody>
                        @foreach ($orders as $o)
                            <tr>
                                <td>{{ $o->created_at->format('d.m.Y') }}</td>
                                <td>{{ $o->service?->title ?? 'Консультация' }}</td>
                                <td class="muted">{{ \Illuminate\Support\Str::limit($o->message, 80) }}</td>
                                <td><span class="status status--{{ $o->status }}">{{ $o->statusLabel() }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 style="margin-top:64px">Мои обсуждения</h2>
            @forelse ($discussions as $d)
                <div class="topic">
                    <div class="avatar">{{ auth()->user()->initials() }}</div>
                    <div>
                        <h3><a href="{{ route('discussions.show', $d) }}" wire:navigate>{{ $d->title }}</a></h3>
                        <div class="small muted">{{ $d->categoryLabel() }} · {{ $d->created_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="stats"><strong>{{ $d->replies_count }}</strong>ответов</div>
                </div>
            @empty
                <div class="empty">Вы ещё не начинали обсуждений.</div>
            @endforelse
        </div>
    </section>
</div>
