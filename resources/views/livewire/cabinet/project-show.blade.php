<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('cabinet') }}" wire:navigate>{{ __('Мои проекты') }}</a></div>
            <div class="eyebrow">{{ $project->service?->tr('title') ?? __('Проект') }}</div>
            <h1>{{ $project->title }}</h1>
            @if ($project->address)<p class="lead">{{ $project->address }}</p>@endif
        </div>
        <x-landmark name="burj" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap split" style="align-items:start">
            <div>
                <div class="facts" style="margin-bottom:32px">
                    <div><span>{{ __('Статус') }}</span><strong>{{ $project->statusLabel() }}</strong></div>
                    <div><span>{{ __('Готовность') }}</span><strong>{{ $project->progress() }}%</strong></div>
                    @if ($project->starts_on)<div><span>{{ __('Начало') }}</span><strong>{{ $project->starts_on->translatedFormat('j F Y') }}</strong></div>@endif
                    @if ($project->ends_on)<div><span>{{ __('Плановое завершение') }}</span><strong>{{ $project->ends_on->translatedFormat('j F Y') }}</strong></div>@endif
                    @if ($project->manager)<div><span>{{ __('Ваш менеджер') }}</span><strong>{{ $project->manager }}</strong></div>@endif
                </div>
                @include('partials.progress', ['value' => $project->progress()])
                @if ($project->note)<p class="muted" style="margin-top:20px;white-space:pre-line">{{ $project->note }}</p>@endif

                <h2 style="margin-top:48px">{{ __('Этапы') }}</h2>
                <ol class="stages">
                    @forelse ($project->stages as $stage)
                        <li class="stage stage--{{ $stage->status }}">
                            <div class="stage-dot"></div>
                            <div>
                                <strong>{{ $stage->title }}</strong>
                                <div class="small muted">{{ $stage->statusLabel() }}@if($stage->ends_on) · {{ __('до :date', ['date' => $stage->ends_on->translatedFormat('j F')]) }}@endif</div>
                                @if ($stage->note)<p class="small" style="margin:6px 0 0">{{ $stage->note }}</p>@endif
                            </div>
                        </li>
                    @empty
                        <li class="muted">{{ __('Этапы скоро появятся.') }}</li>
                    @endforelse
                </ol>
            </div>
            <div>
                <h2>{{ __('Новости с объекта') }}</h2>
                @forelse ($project->updates as $u)
                    <div class="update">
                        <div class="small muted">{{ $u->created_at->translatedFormat('j F Y, H:i') }}</div>
                        <p style="white-space:pre-line;margin:6px 0 0">{{ $u->body }}</p>
                        @if ($u->image)<a href="{{ $u->image }}" target="_blank" rel="noopener"><img src="{{ $u->image }}" alt="" loading="lazy"></a>@endif
                    </div>
                @empty
                    <div class="empty">{{ __('Здесь будут фото и заметки о ходе работ.') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
