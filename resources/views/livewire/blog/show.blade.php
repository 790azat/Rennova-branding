@push('head')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->tr('title') }}">
    <meta property="og:description" content="{{ $post->tr('excerpt') }}">
    @if ($post->cover_image)<meta property="og:image" content="{{ url($post->cover_image) }}">@endif
    <link rel="canonical" href="{{ route('blog.show', $post) }}">
    <script type="application/ld+json">{!! json_encode([
        '@'.'context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $post->tr('title'), 'description' => $post->tr('excerpt'),
        'datePublished' => $post->published_at?->toAtomString(), 'dateModified' => $post->updated_at?->toAtomString(),
        'inLanguage' => app()->getLocale(), 'image' => $post->cover_image ? url($post->cover_image) : null,
        'author' => ['@type' => 'Organization', 'name' => 'Rennova'],
        'publisher' => ['@type' => 'Organization', 'name' => 'Rennova by Metruminvest'],
        'mainEntityOfPage' => route('blog.show', $post),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush
<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / <a href="{{ route('blog') }}" wire:navigate>{{ __('Блог') }}</a></div>
            <div class="eyebrow">@if($post->categoryLabel()){{ $post->categoryLabel() }} · @endif{{ $post->published_at?->translatedFormat('j F Y') }} · {{ __(':n мин чтения', ['n' => $post->readingMinutes()]) }}</div>
            <h1>{{ $post->tr('title') }}</h1>
            @if ($post->tr('excerpt'))<p class="lead">{{ $post->tr('excerpt') }}</p>@endif
            @unless ($post->isPublished())<span class="status status--new">{{ __('Черновик: виден только администраторам') }}</span>@endunless
        </div>
        <x-landmark :name="$post->landmark ?? 'eiffel'" stroke="0.8" />
    </section>

    <article class="section">
        <div class="wrap" style="max-width:820px">
            @if ($post->cover_image)<img src="{{ $post->cover_image }}" alt="{{ $post->tr('title') }}" class="project-hero-img" style="margin-bottom:40px">@endif
            <div class="prose">{{ $post->html() }}</div>
            <div class="cta-band">
                <div>
                    <h3>{{ __('Планируете ремонт?') }}</h3>
                    <p class="muted">{{ __('Оцените стоимость за минуту или обсудите проект со специалистом.') }}</p>
                </div>
                <div class="hero-actions" style="margin:0">
                    <a href="{{ route('calculator') }}" class="btn" wire:navigate>{{ __('Рассчитать стоимость') }}</a>
                    <a href="{{ route('booking') }}" class="btn btn--ghost" wire:navigate>{{ __('Консультация') }}</a>
                </div>
            </div>
        </div>
    </article>

    @if ($more->isNotEmpty())
        <section class="section section--bone">
            <div class="wrap">
                <div class="section-head"><h2>{{ __('Читайте также') }}</h2></div>
                <div class="grid grid-3">
                    @foreach ($more as $post)
                        @include('partials.post-card')
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
