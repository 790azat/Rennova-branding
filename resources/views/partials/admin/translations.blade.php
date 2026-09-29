<div class="tr-fields">
    <div class="sep-title">{{ __('Переводы') }} <span class="muted">{{ __('Если поле пустое, показывается русский текст.') }}</span></div>
    @foreach ($tr as $locale => $values)
        <details @if(array_filter($values)) open @endif>
            <summary>{{ ['en' => 'English', 'hy' => 'Հայերեն'][$locale] ?? strtoupper($locale) }}</summary>
            @foreach ($fields as $field => $label)
                <div>
                    <label>{{ __($label) }}</label>
                    @if (in_array($field, $long ?? [], true))
                        <textarea class="input" style="min-height:80px" wire:model="tr.{{ $locale }}.{{ $field }}" lang="{{ $locale }}"></textarea>
                    @else
                        <input class="input" wire:model="tr.{{ $locale }}.{{ $field }}" lang="{{ $locale }}">
                    @endif
                </div>
            @endforeach
        </details>
    @endforeach
</div>
