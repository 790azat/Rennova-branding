<div>
    <div class="admin-top"><h1>{{ __('Отзывы') }}</h1></div>
    <div class="filters">
        <button class="chip {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">{{ __('Все') }}</button>
        @foreach (\App\Models\Review::STATUSES as $key => $label)
            <button class="chip {{ $status === $key ? 'active' : '' }}" wire:click="$set('status', '{{ $key }}')">{{ __($label) }}</button>
        @endforeach
    </div>
    <div class="grid" style="gap:16px">
        @forelse ($reviews as $r)
            <div class="panel" style="padding:24px" wire:key="arv-{{ $r->id }}">
                <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap">
                    <div>
                        <span class="stars">@for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= $r->rating ? 'on' : '' }}">★</span>@endfor</span>
                        <strong style="margin-left:8px">{{ $r->name }}</strong>
                        <span class="small muted">@if($r->user) · {{ $r->user->email }}@endif @if($r->service) · {{ $r->service->tr('title') }}@endif · {{ $r->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <span class="status status--{{ $r->status }}">{{ $r->statusLabel() }}</span>
                </div>
                <p style="margin:14px 0;white-space:pre-line">{{ $r->body }}</p>
                <div class="form-row" style="align-items:end">
                    <div><label>{{ __('Ответ Rennova (публикуется под отзывом)') }}</label><textarea class="input" style="min-height:60px" wire:model="replies.{{ $r->id }}"></textarea></div>
                    <div class="actions">
                        <button class="btn btn--ghost btn--sm" wire:click="saveReply({{ $r->id }})">{{ session('saved-'.$r->id) ? __('Сохранено') : __('Сохранить ответ') }}</button>
                        @if ($r->status !== 'approved')<button class="btn btn--sm" wire:click="setStatus({{ $r->id }}, 'approved')">{{ __('Опубликовать') }}</button>@endif
                        @if ($r->status !== 'rejected')<button class="btn btn--ghost btn--sm" wire:click="setStatus({{ $r->id }}, 'rejected')">{{ __('Отклонить') }}</button>@endif
                        <button class="btn btn--danger btn--sm" wire:click="delete({{ $r->id }})" wire:confirm="{{ __('Удалить отзыв?') }}">{{ __('Удалить') }}</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">{{ __('Отзывов нет') }}</div>
        @endforelse
    </div>
    <div class="pagination">{{ $reviews->links('partials.pagination') }}</div>
</div>
