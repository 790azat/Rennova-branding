<a href="{{ route('portfolio.show', $project) }}" class="project-card" wire:navigate wire:key="pc-{{ $project->id }}">
    <div class="project-media">
        @if ($project->coverUrl())
            <img src="{{ $project->coverUrl() }}" alt="{{ $project->tr('title') }}" loading="lazy">
        @else
            <div class="project-placeholder"><x-landmark :name="$project->landmark ?? 'cascade'" stroke="0.9" /></div>
        @endif
        @if ($project->hasBeforeAfter())<span class="badge-ex">{{ __('До / после') }}</span>@endif
    </div>
    <div class="project-body">
        <div class="meta">{{ $project->categoryLabel() }}@if($project->tr('location')) · {{ $project->tr('location') }}@endif @if($project->year) · {{ $project->year }}@endif</div>
        <h3>{{ $project->tr('title') }}</h3>
        @if ($project->tr('summary'))<p class="muted small">{{ $project->tr('summary') }}</p>@endif
        <span class="link-arrow">{{ __('Смотреть проект') }}</span>
    </div>
</a>
