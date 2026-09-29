@php
    $links = [
        'facebook' => \App\Models\Setting::get('facebook_url', config('rennova.facebook_url')),
        'instagram' => \App\Models\Setting::get('instagram_url', config('rennova.instagram_url')),
    ];
@endphp
<div class="socials">
    @foreach ($links as $network => $url)
        <a class="social" href="{{ $url ?: '#' }}" @if($url) target="_blank" rel="noopener" @else aria-disabled="true" onclick="return false" title="{{ __('Скоро') }}" @endif aria-label="{{ ucfirst($network) }}">
            @if ($network === 'facebook')
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.3-1.5 1.6-1.5h1.7V4.4c-.3 0-1.3-.1-2.5-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H7.5V14h2.7v8h3.3z"/></svg>
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            @endif
        </a>
    @endforeach
</div>
