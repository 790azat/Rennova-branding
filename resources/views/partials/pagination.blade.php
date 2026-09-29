@if ($paginator->hasPages())
    <nav style="display:flex;gap:8px;justify-content:center;margin-top:32px;flex-wrap:wrap" aria-label="Страницы">
        @if (! $paginator->onFirstPage())
            <button class="chip" wire:click="previousPage('{{ $paginator->getPageName() }}')">← Назад</button>
        @endif
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <button class="chip {{ $page == $paginator->currentPage() ? 'active' : '' }}" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</button>
                @endforeach
            @else
                <span class="chip" style="border:0">…</span>
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <button class="chip" wire:click="nextPage('{{ $paginator->getPageName() }}')">Вперёд →</button>
        @endif
    </nav>
@endif
