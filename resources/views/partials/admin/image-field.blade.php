{{-- Image input: paste a URL or upload a file (resized in the browser, stored in the database). --}}
@php $value = data_get($this, $model); $multiple = $multiple ?? false; @endphp
<div class="image-field" x-data="{ busy: false, error: '' }">
    <label>{{ __($label) }}</label>
    @if ($multiple)
        <textarea class="input" style="min-height:80px" wire:model="{{ $model }}" placeholder="{{ __('По одной ссылке в строке') }}"></textarea>
        @if (filled($value))
            <div class="thumbs">@foreach (preg_split('/\s+/', trim($value)) as $u)<img src="{{ $u }}" alt="">@endforeach</div>
        @endif
    @else
        <div class="image-field-row">
            @if (filled($value))<img src="{{ $value }}" alt="" class="thumb">@endif
            <input class="input" wire:model.blur="{{ $model }}" placeholder="{{ __('Ссылка или загрузите файл') }}">
        </div>
    @endif
    <div class="image-field-actions">
        <label class="btn btn--ghost btn--sm" style="margin:0">
            <span x-show="!busy">{{ $multiple ? __('Добавить фото') : __('Загрузить фото') }}</span><span x-show="busy" x-cloak>{{ __('Загрузка…') }}</span>
            <input type="file" accept="image/jpeg,image/png,image/webp" hidden @if($multiple) multiple @endif
                   @change="busy = true; error = ''; rnUpload([...$event.target.files]).then(urls => {
                        @if ($multiple)
                            const cur = ($wire.get('{{ $model }}') || '').trim(); $wire.set('{{ $model }}', (cur ? cur + '\n' : '') + urls.join('\n'));
                        @else
                            $wire.set('{{ $model }}', urls[0]);
                        @endif
                   }).catch(e => error = e.message).finally(() => { busy = false; $event.target.value = '' })">
        </label>
        @if (filled($value) && ! $multiple)<button type="button" class="btn btn--ghost btn--sm" wire:click="$set('{{ $model }}', '')">{{ __('Убрать') }}</button>@endif
    </div>
    <div class="error" x-show="error" x-text="error"></div>
    @error($model)<div class="error">{{ $message }}</div>@enderror
</div>
