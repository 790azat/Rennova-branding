<a href="{{ route('blog.show', $post) }}" class="post-card" wire:navigate wire:key="post-{{ $post->id }}">
    <div class="post-media">
        @if ($post->cover_image)
            <img src="{{ $post->cover_image }}" alt="{{ $post->tr('title') }}" loading="lazy">
        @else
            <div class="project-placeholder"><x-landmark :name="$post->landmark ?? 'eiffel'" stroke="0.9" /></div>
        @endif
    </div>
    <div class="project-body">
        <div class="meta">@if($post->categoryLabel()){{ $post->categoryLabel() }} · @endif{{ $post->published_at?->translatedFormat('j F Y') }}</div>
        <h3>{{ $post->tr('title') }}</h3>
        @if ($post->tr('excerpt'))<p class="muted small">{{ $post->tr('excerpt') }}</p>@endif
        <span class="link-arrow">{{ __('Читать') }}</span>
    </div>
</a>
