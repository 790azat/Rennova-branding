<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('profile') }}" wire:navigate>{{ __('Профиль') }}</a></div>
            <div class="eyebrow">{{ __('Личный кабинет') }}</div>
            <h1>{{ __('Мои проекты') }}</h1>
            <p class="lead">{{ __('Этапы работ, сроки и новости с объекта в одном месте.') }}</p>
        </div>
        <x-landmark name="burj" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            @if ($appointments->isNotEmpty())
                <div class="alert" style="margin-bottom:32px">
                    @foreach ($appointments as $a)
                        <div>{{ __('Консультация') }}: <strong>{{ $a->date->translatedFormat('j F') }}, {{ $a->time }}</strong> · {{ $a->formatLabel() }} · {{ $a->statusLabel() }}</div>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-2">
                @forelse ($projects as $p)
                    <a href="{{ route('cabinet.project', $p) }}" class="card cabinet-card" wire:navigate wire:key="cp-{{ $p->id }}">
                        <div class="meta small muted">{{ $p->service?->tr('title') }}@if($p->address) · {{ $p->address }}@endif</div>
                        <h3>{{ $p->title }}</h3>
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin:14px 0 8px">
                            <span class="status status--{{ $p->status }}">{{ $p->statusLabel() }}</span>
                            <strong>{{ $p->progress() }}%</strong>
                        </div>
                        @include('partials.progress', ['value' => $p->progress()])
                        @if ($stage = $p->currentStage())
                            <p class="small muted" style="margin:12px 0 0">{{ __('Сейчас') }}: {{ $stage->title }}</p>
                        @endif
                    </a>
                @empty
                    <div class="empty" style="grid-column:1/-1">
                        {{ __('Когда мы начнём работу по вашему объекту, здесь появятся этапы и статус проекта.') }}
                        <div class="hero-actions" style="justify-content:center">
                            <a href="{{ route('booking') }}" class="btn" wire:navigate>{{ __('Записаться на консультацию') }}</a>
                            <a href="{{ route('calculator') }}" class="btn btn--ghost" wire:navigate>{{ __('Рассчитать стоимость') }}</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
