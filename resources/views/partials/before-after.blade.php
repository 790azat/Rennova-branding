<div class="ba" x-data="{ pos: 50 }">
    <img src="{{ $after }}" alt="{{ __('После') }}" class="ba-img" loading="lazy">
    <div class="ba-before" :style="`clip-path: inset(0 ${100 - pos}% 0 0)`">
        <img src="{{ $before }}" alt="{{ __('До') }}" class="ba-img" loading="lazy">
    </div>
    <div class="ba-line" :style="`left: ${pos}%`"><span>⇆</span></div>
    <span class="ba-tag ba-tag--before">{{ __('До') }}</span>
    <span class="ba-tag ba-tag--after">{{ __('После') }}</span>
    <input type="range" min="0" max="100" x-model="pos" aria-label="{{ __('Сравнить до и после') }}">
</div>
