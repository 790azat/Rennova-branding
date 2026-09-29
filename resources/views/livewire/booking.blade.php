<div>
    <section class="page-hero">
        <div class="wrap">
            <div class="crumbs"><a href="{{ route('home') }}" wire:navigate>{{ __('Главная') }}</a> / {{ __('Консультация') }}</div>
            <div class="eyebrow">{{ __('Консультация') }}</div>
            <h1>{{ __('Запишитесь на бесплатную консультацию') }}</h1>
            <p class="lead">{{ __('Выберите удобный формат, дату и время. Специалист Rennova обсудит задачу, сроки и бюджет.') }}</p>
        </div>
        <x-landmark name="taj" stroke="0.8" />
    </section>

    <section class="section">
        <div class="wrap" style="max-width:980px">
            @if ($booked)
                <div class="panel booked">
                    <div class="eyebrow">{{ __('Вы записаны') }}</div>
                    <h2>{{ $booked->date->translatedFormat('j F') }}, {{ $booked->time }}</h2>
                    <p class="lead">{{ $booked->formatLabel() }}@if($booked->service) · {{ $booked->service->tr('title') }}@endif</p>
                    <p class="muted">{{ __('Мы позвоним по номеру :phone, чтобы подтвердить запись.', ['phone' => $booked->phone]) }}</p>
                    <button class="btn btn--ghost" wire:click="$set('bookedId', null)">{{ __('Записаться ещё раз') }}</button>
                </div>
            @else
                <form wire:submit="book" class="form booking">
                    <div>
                        <label>{{ __('Формат') }}</label>
                        <div class="choice-grid">
                            @foreach (\App\Models\Appointment::FORMATS as $key => $label)
                                <label class="choice {{ $format === $key ? 'active' : '' }}"><input type="radio" wire:model.live="format" value="{{ $key }}"><strong>{{ __($label) }}</strong></label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label>{{ __('Дата') }}</label>
                        <div class="days">
                            @forelse ($days as $d => $day)
                                <button type="button" class="day {{ $date === $d ? 'active' : '' }}" wire:click="$set('date', '{{ $d }}')" wire:key="d-{{ $d }}">
                                    <span>{{ $day->translatedFormat('D') }}</span><strong>{{ $day->day }}</strong><span>{{ $day->translatedFormat('M') }}</span>
                                </button>
                            @empty
                                <div class="empty">{{ __('Свободных дат нет. Позвоните нам, и мы что-нибудь придумаем.') }}</div>
                            @endforelse
                        </div>
                        @error('date')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    @if ($timeSlots)
                        <div>
                            <label>{{ __('Время') }}</label>
                            <div class="slots">
                                @foreach ($timeSlots as $t => $free)
                                    <button type="button" class="chip {{ $time === $t ? 'active' : '' }}" @disabled(! $free) wire:click="$set('time', '{{ $t }}')" wire:key="t-{{ $date }}-{{ $t }}">{{ $t }}</button>
                                @endforeach
                            </div>
                            @error('time')<div class="error">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    <div>
                        <label for="bk-service">{{ __('Что обсудим') }}</label>
                        <select id="bk-service" class="input" wire:model="serviceId">
                            <option value="">{{ __('Пока не знаю') }}</option>
                            @foreach ($services as $s)<option value="{{ $s->id }}">{{ $s->tr('title') }}</option>@endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div><label for="bk-name">{{ __('Имя') }}</label><input id="bk-name" class="input" wire:model="name">@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                        <div><label for="bk-phone">{{ __('Телефон') }}</label><input id="bk-phone" class="input" wire:model="phone">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
                    </div>
                    <div><label for="bk-email">Email</label><input id="bk-email" type="email" class="input" wire:model="email">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                    <div><label for="bk-comment">{{ __('Комментарий') }}</label><textarea id="bk-comment" class="input" style="min-height:90px" wire:model="comment" placeholder="{{ __('Адрес объекта, площадь, пожелания') }}"></textarea></div>
                    <div><button class="btn" type="submit" wire:loading.attr="disabled">{{ __('Записаться') }}</button></div>
                </form>
            @endif
        </div>
    </section>
</div>
