<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="eyebrow">{{ __('Личный кабинет') }}</div>
            <h1>{{ $name }}</h1>
            <p class="lead">{{ __('Ваши данные, заявки на услуги и обсуждения.') }}</p>
            <a href="{{ route('cabinet') }}" class="btn btn--light" style="margin-top:24px" wire:navigate>{{ __('Мои проекты и статус работ') }}</a>
        </div>
        <x-landmark name="bigben" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            @if (session('saved'))<div class="alert">{{ session('saved') }}</div>@endif
            <div class="grid grid-2" style="align-items:start">
                <div class="panel">
                    <h3>{{ __('Данные профиля') }}</h3>
                    <form wire:submit="save" class="form">
                        <div><label>{{ __('Имя') }}</label><input class="input" wire:model="name">@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>Email</label><input class="input" type="email" wire:model="email">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Телефон') }}</label><input class="input" wire:model="phone">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><button class="btn" type="submit">{{ __('Сохранить') }}</button></div>
                    </form>
                </div>
                <div class="panel">
                    <h3>{{ __('Смена пароля') }}</h3>
                    <form wire:submit="changePassword" class="form">
                        <div><label>{{ __('Текущий пароль') }}</label><input class="input" type="password" wire:model="current_password">@error('current_password')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Новый пароль') }}</label><input class="input" type="password" wire:model="password">@error('password')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label>{{ __('Повторите пароль') }}</label><input class="input" type="password" wire:model="password_confirmation"></div>
                        <div><button class="btn" type="submit">{{ __('Обновить пароль') }}</button></div>
                    </form>
                </div>
            </div>

            <h2 style="margin-top:64px">{{ __('Мои заявки на услуги') }}</h2>
            @if ($orders->isEmpty())
                <div class="empty">{{ __('Заявок пока нет.') }} <a href="{{ route('services.index') }}" class="link-arrow" wire:navigate>{{ __('Выбрать услугу') }}</a></div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>{{ __('Дата') }}</th><th>{{ __('Услуга') }}</th><th>{{ __('Сообщение') }}</th><th>{{ __('Статус') }}</th></tr></thead>
                        <tbody>
                        @foreach ($orders as $o)
                            <tr>
                                <td>{{ $o->created_at->format('d.m.Y') }}</td>
                                <td>{{ $o->service?->tr('title') ?? __('Консультация') }}</td>
                                <td class="muted">{{ \Illuminate\Support\Str::limit($o->message, 80) }}</td>
                                <td><span class="status status--{{ $o->status }}">{{ $o->statusLabel() }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 style="margin-top:64px">{{ __('Мои обсуждения') }}</h2>
            @forelse ($discussions as $d)
                <div class="topic">
                    <div class="avatar">{{ auth()->user()->initials() }}</div>
                    <div>
                        <h3><a href="{{ route('discussions.show', $d) }}" wire:navigate>{{ $d->title }}</a></h3>
                        <div class="small muted">{{ $d->categoryLabel() }} · {{ $d->created_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="stats"><strong>{{ $d->replies_count }}</strong>{{ __('ответов') }}</div>
                </div>
            @empty
                <div class="empty">{{ __('Вы ещё не начинали обсуждений.') }}</div>
            @endforelse
        </div>
    </section>
</div>
