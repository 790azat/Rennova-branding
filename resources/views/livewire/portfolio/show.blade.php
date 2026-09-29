<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('portfolio') }}" wire:navigate>{{ __('Портфолио') }}</a></div>
            <div class="eyebrow">{{ $project->categoryLabel() }}</div>
            <h1>{{ $project->tr('title') }}</h1>
            @if ($project->tr('summary'))<p class="lead">{{ $project->tr('summary') }}</p>@endif
            @unless ($project->is_published)<span class="status status--new">{{ __('Черновик: виден только администраторам') }}</span>@endunless
        </div>
        <x-landmark :name="$project->landmark ?? 'cascade'" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap">
            <div class="facts">
                @if ($project->tr('location'))<div><span>{{ __('Где') }}</span><strong>{{ $project->tr('location') }}</strong></div>@endif
                @if ($project->area)<div><span>{{ __('Площадь') }}</span><strong>{{ $project->area }} {{ __('м²') }}</strong></div>@endif
                @if ($project->tr('duration'))<div><span>{{ __('Срок') }}</span><strong>{{ $project->tr('duration') }}</strong></div>@endif
                @if ($project->year)<div><span>{{ __('Год') }}</span><strong>{{ $project->year }}</strong></div>@endif
                @if ($project->service)<div><span>{{ __('Услуга') }}</span><strong><a href="{{ route('services.show', $project->service) }}" wire:navigate>{{ $project->service->tr('title') }}</a></strong></div>@endif
            </div>

            @if ($project->hasBeforeAfter())
                <h2 style="margin-top:56px">{{ __('До и после') }}</h2>
                <p class="muted">{{ __('Потяните ползунок, чтобы сравнить.') }}</p>
                @include('partials.before-after', ['before' => $project->before_image, 'after' => $project->after_image])
            @elseif ($project->coverUrl())
                <img src="{{ $project->coverUrl() }}" alt="{{ $project->tr('title') }}" class="project-hero-img">
            @endif

            @if ($project->tr('description'))
                <div class="prose" style="margin-top:48px">{!! nl2br(e($project->tr('description'))) !!}</div>
            @endif

            @if (! empty($project->gallery))
                <div class="gallery">
                    @foreach ($project->gallery as $img)
                        <a href="{{ $img }}" target="_blank" rel="noopener"><img src="{{ $img }}" alt="" loading="lazy"></a>
                    @endforeach
                </div>
            @endif

            <div class="cta-band">
                <div>
                    <h3>{{ __('Хотите так же?') }}</h3>
                    <p class="muted">{{ __('Рассчитайте ориентировочную стоимость или запишитесь на бесплатную консультацию.') }}</p>
                </div>
                <div class="hero-actions" style="margin:0">
                    <a href="{{ route('calculator', ['service' => $project->service?->slug]) }}" class="btn" wire:navigate>{{ __('Рассчитать стоимость') }}</a>
                    <a href="{{ route('booking') }}" class="btn btn--ghost" wire:navigate>{{ __('Записаться на консультацию') }}</a>
                </div>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section section--bone">
            <div class="wrap">
                <div class="section-head"><h2>{{ __('Похожие проекты') }}</h2></div>
                <div class="grid grid-3">
                    @foreach ($related as $project)
                        @include('partials.project-card')
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
