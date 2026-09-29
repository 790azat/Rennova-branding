<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Отзывы') }}</div>
            <div class="eyebrow">{{ __('Отзывы') }}</div>
            <h1>{{ __('Что говорят наши клиенты') }}</h1>
            @if ($count)
                <p class="lead"><span class="stars stars--light">★</span> {{ $average }} / 5 · {{ trans_choice(':count отзыв|:count отзыва|:count отзывов', $count) }}</p>
            @else
                <p class="lead">{{ __('Работали с нами? Расскажите, как всё прошло.') }}</p>
            @endif
        </div>
        <x-landmark name="opera" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="grid" style="gap:16px">
                    @forelse ($reviews as $review)
                        @include('partials.review-card')
                    @empty
                        <div class="empty">{{ __('Отзывов пока нет. Будьте первым!') }}</div>
                    @endforelse
                </div>
                <div class="pagination">{{ $reviews->links('partials.pagination') }}</div>
            </div>
            <div class="panel" id="review-form">
                <h3>{{ __('Оставить отзыв') }}</h3>
                @auth
                    @if ($sent)<div class="alert">{{ __('Спасибо! Отзыв появится на сайте после проверки.') }}</div>@endif
                    <form wire:submit="submit" class="form">
                        <div>
                            <label>{{ __('Оценка') }}</label>
                            <div class="stars stars--input">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="{{ $i <= $rating ? 'on' : '' }}" wire:click="$set('rating', {{ $i }})" aria-label="{{ $i }}">★</button>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label for="rv-service">{{ __('Услуга') }}</label>
                            <select id="rv-service" class="input" wire:model="serviceId">
                                <option value="">{{ __('Не указывать') }}</option>
                                @foreach ($services as $s)<option value="{{ $s->id }}">{{ $s->tr('title') }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label for="rv-body">{{ __('Отзыв') }}</label>
                            <textarea id="rv-body" class="input" wire:model="body" placeholder="{{ __('Что понравилось, что можно улучшить') }}"></textarea>
                            @error('body')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div><button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Отправить отзыв') }}</button></div>
                    </form>
                    @if ($mine->isNotEmpty())
                        <div style="margin-top:28px">
                            <div class="small muted" style="margin-bottom:8px">{{ __('Ваши отзывы') }}</div>
                            @foreach ($mine as $r)
                                <div class="small" style="padding:8px 0;border-top:1px solid var(--line)"><span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span> {{ \Illuminate\Support\Str::limit($r->body, 80) }}</div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="muted">{{ __('Чтобы оставить отзыв, войдите в аккаунт.') }}</p>
                    <div class="hero-actions" style="margin-top:12px">
                        <a href="{{ route('login') }}" class="btn" wire:navigate>{{ __('Войти') }}</a>
                        <a href="{{ route('register') }}" class="btn btn--ghost" wire:navigate>{{ __('Регистрация') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</div>
