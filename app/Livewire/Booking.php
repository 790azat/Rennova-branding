<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Запись на консультацию')]
class Booking extends Component
{
    public string $format = 'office';

    public ?int $serviceId = null;

    public string $date = '';

    public string $time = '';

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $comment = '';

    public ?int $bookedId = null;

    public function mount(): void
    {
        $this->date = (string) array_key_first($this->days());
        if ($user = auth()->user()) {
            $this->name = $user->name;
            $this->phone = (string) $user->phone;
            $this->email = $user->email;
        }
    }

    /** Bookable days: date => Carbon. Today counts only while slots remain. */
    public function days(): array
    {
        $cfg = config('rennova.booking');
        $days = [];
        $day = CarbonImmutable::today($cfg['timezone']);
        for ($i = 0; $i <= $cfg['days_ahead']; $i++, $day = $day->addDay()) {
            if (in_array($day->isoWeekday(), $cfg['weekdays'], true) && $this->slotsFor($day->toDateString())) {
                $days[$day->toDateString()] = $day;
            }
        }

        return $days;
    }

    /** time => free? for a date; past times of today are dropped. */
    public function slotsFor(string $date): array
    {
        $taken = Appointment::takenTimes($date);
        $slots = [];
        foreach (config('rennova.booking.times') as $t) {
            // Slots less than an hour away (office time) can no longer be booked.
            if (CarbonImmutable::parse("{$date} {$t}", config('rennova.booking.timezone'))->lte(now()->addHour())) {
                continue;
            }
            $slots[$t] = ! in_array($t, $taken, true);
        }

        return $slots;
    }

    public function updatedDate(): void
    {
        $this->time = '';
    }

    public function book(): void
    {
        $key = 'booking:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('time', __('Слишком много заявок. Попробуйте чуть позже.'));

            return;
        }

        $this->validate([
            'format' => 'required|in:'.implode(',', array_keys(Appointment::FORMATS)),
            'serviceId' => 'nullable|exists:services,id',
            'date' => 'required|in:'.implode(',', array_keys($this->days())),
            'time' => 'required|string',
            'name' => 'required|string|max:120',
            'phone' => 'required|string|min:5|max:40',
            'email' => 'nullable|email|max:160',
            'comment' => 'nullable|string|max:2000',
        ], [], ['date' => __('дата'), 'time' => __('время'), 'name' => __('имя'), 'phone' => __('телефон')]);

        if (! ($this->slotsFor($this->date)[$this->time] ?? false)) {
            $this->addError('time', __('Это время уже занято, выберите другое.'));

            return;
        }
        RateLimiter::hit($key, 600);

        $this->bookedId = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $this->serviceId,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'date' => $this->date,
            'time' => $this->time,
            'format' => $this->format,
            'comment' => $this->comment ?: null,
        ])->id;
    }

    public function render()
    {
        return view('livewire.booking', [
            'days' => $this->days(),
            'timeSlots' => $this->date ? $this->slotsFor($this->date) : [],
            'services' => Service::query()->active()->get(['id', 'title', 'translations']),
            'booked' => $this->bookedId ? Appointment::find($this->bookedId) : null,
        ]);
    }
}
