<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('discussions.index') }}" wire:navigate>{{ __('Обсуждения') }}</a> / {{ $discussion->categoryLabel() }}</div>
            <div class="eyebrow">{{ $discussion->categoryLabel() }}</div>
            <h1>{{ $discussion->title }}</h1>
        </div>
        <x-landmark :name="['design' => 'opera', 'architecture' => 'empire', 'renovation' => 'eiffel'][$discussion->category] ?? 'eiffel'" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap" style="max-width:900px">
            <div class="post" style="border-top:1px solid var(--line)">
                <div class="avatar">{{ $discussion->user->initials() }}</div>
                <div>
                    <div class="post-meta">
                        <strong>{{ $discussion->user->name }}</strong>
                        @if ($discussion->user->isAdmin())<span class="role-badge">Rennova</span>@endif
                        {{ $discussion->created_at->translatedFormat('j F Y, H:i') }}
                    </div>
                    <div class="post-body">{{ $discussion->body }}</div>
                </div>
            </div>

            @foreach ($replies as $reply)
                <div class="post" wire:key="r-{{ $reply->id }}">
                    <div class="avatar" @if($reply->user->isAdmin()) style="background:var(--forest)" @endif>{{ $reply->user->initials() }}</div>
                    <div>
                        <div class="post-meta">
                            <strong>{{ $reply->user->name }}</strong>
                            @if ($reply->user->isAdmin())<span class="role-badge">Rennova</span>@endif
                            {{ $reply->created_at->diffForHumans() }}
                            @auth
                                @if (auth()->user()->isAdmin() || auth()->id() === $reply->user_id)
                                    · <a href="#" wire:click.prevent="deleteReply({{ $reply->id }})" wire:confirm="{{ __('Удалить ответ?') }}" style="color:var(--danger)">{{ __('удалить') }}</a>
                                @endif
                            @endauth
                        </div>
                        <div class="post-body">{{ $reply->body }}</div>
                    </div>
                </div>
            @endforeach

            <div style="margin-top:40px">
                @auth
                    @if ($discussion->is_closed && ! auth()->user()->isAdmin())
                        <div class="alert">{{ __('Обсуждение закрыто для новых ответов.') }}</div>
                    @else
                        <form wire:submit="reply" class="form panel">
                            <label for="reply">{{ __('Ваш ответ') }}</label>
                            <textarea id="reply" class="input" wire:model="body" placeholder="{{ __('Поделитесь мнением или опытом') }}"></textarea>
                            @error('body')<div class="error">{{ $message }}</div>@enderror
                            <div><button class="btn" type="submit">{{ __('Ответить') }}</button></div>
                        </form>
                    @endif
                @else
                    <div class="panel" style="text-align:center">
                        <p>{{ __('Чтобы участвовать в обсуждении, войдите в аккаунт.') }}</p>
                        <a href="{{ route('login') }}" class="btn" wire:navigate>{{ __('Войти') }}</a>
                        <a href="{{ route('register') }}" class="btn btn--ghost" wire:navigate>{{ __('Регистрация') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</div>
