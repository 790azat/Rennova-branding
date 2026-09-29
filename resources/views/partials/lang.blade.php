<div class="lang-switch" aria-label="{{ __('Язык') }}">
    @foreach (config('rennova.locales') as $code => $label)
        <a href="{{ route('locale', $code) }}" lang="{{ $code }}" class="{{ app()->getLocale() === $code ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>
