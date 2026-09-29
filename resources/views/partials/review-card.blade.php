<div class="review-card" wire:key="rv-{{ $review->id }}">
    <div class="stars" aria-label="{{ __('Оценка: :n из 5', ['n' => $review->rating]) }}">@for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= $review->rating ? 'on' : '' }}">★</span>@endfor</div>
    <p class="review-body">{{ $review->body }}</p>
    <div class="review-meta"><strong>{{ $review->name }}</strong>@if($review->service) · {{ $review->service->tr('title') }}@endif · {{ $review->created_at->translatedFormat('F Y') }}</div>
    @if ($review->reply)
        <div class="review-reply"><strong>{{ __('Ответ Rennova:') }}</strong> {{ $review->reply }}</div>
    @endif
</div>
