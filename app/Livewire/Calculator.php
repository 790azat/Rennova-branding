<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Калькулятор стоимости')]
class Calculator extends Component
{
    #[Url(as: 'service')]
    public string $serviceSlug = '';

    #[Url]
    public int|string $area = 60;

    #[Url]
    public string $property = 'apartment';

    public string $condition = 'new';

    public array $extras = [];

    public bool $urgent = false;

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $comment = '';

    public bool $sent = false;

    public function mount(): void
    {
        if (! $this->services()->contains('slug', $this->serviceSlug)) {
            $this->serviceSlug = (string) $this->services()->first()?->slug;
        }
        if ($user = auth()->user()) {
            $this->name = $user->name;
            $this->phone = (string) $user->phone;
            $this->email = $user->email;
        }
    }

    /** Services priced per m² take part in the calculator. */
    public function services(): Collection
    {
        return Service::query()->active()->whereNotNull('price_from')->where('price_unit', 'м²')->get();
    }

    public function updatedServiceSlug(): void
    {
        $this->extras = array_values(array_intersect($this->extras, array_keys($this->availableExtras())));
    }

    public function service(): ?Service
    {
        return $this->services()->firstWhere('slug', $this->serviceSlug);
    }

    public function availableExtras(): array
    {
        $category = $this->service()?->category;

        return array_filter(config('rennova.calculator.extras'), fn ($e) => in_array($category, $e[2], true));
    }

    public function usesCondition(): bool
    {
        return in_array($this->service()?->category, ['renovation', 'bundle', 'cleaning'], true);
    }

    /** @return array{0:int,1:int}|null [from, to] in AMD */
    public function estimate(): ?array
    {
        $service = $this->service();
        $cfg = config('rennova.calculator');
        $area = (int) $this->area;
        if (! $service || $area < $cfg['min_area'] || $area > $cfg['max_area']) {
            return null;
        }

        $rate = $service->price_from * ($cfg['property'][$this->property][1] ?? 1);
        if ($this->usesCondition()) {
            $rate *= $cfg['condition'][$this->condition][1] ?? 1;
        }
        foreach ($this->extras as $key) {
            $rate += $this->availableExtras()[$key][1] ?? 0;
        }
        $total = $rate * $area * ($this->urgent ? $cfg['urgent'] : 1);

        $round = fn (float $v) => (int) (round($v / 10000) * 10000);

        return [$round($total), $round($total * $cfg['spread'])];
    }

    public function submit(): void
    {
        $key = 'calc:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('phone', __('Слишком много заявок. Попробуйте чуть позже.'));

            return;
        }

        $cfg = config('rennova.calculator');
        $this->validate([
            'serviceSlug' => 'required|in:'.$this->services()->pluck('slug')->implode(','),
            'area' => 'required|integer|min:'.$cfg['min_area'].'|max:'.$cfg['max_area'],
            'property' => 'required|in:'.implode(',', array_keys($cfg['property'])),
            'condition' => 'required|in:'.implode(',', array_keys($cfg['condition'])),
            'extras.*' => 'in:'.implode(',', array_keys($cfg['extras'])),
            'name' => 'required|string|max:120',
            'phone' => 'required|string|min:5|max:40',
            'email' => 'nullable|email|max:160',
            'comment' => 'nullable|string|max:2000',
        ], [], [
            'area' => __('площадь'), 'name' => __('имя'), 'phone' => __('телефон'), 'comment' => __('комментарий'),
        ]);
        RateLimiter::hit($key, 600);

        [$from, $to] = $this->estimate();
        $service = $this->service();
        $details = [
            'area' => (int) $this->area,
            'property' => $this->property,
            'condition' => $this->usesCondition() ? $this->condition : null,
            'extras' => array_values($this->extras),
            'urgent' => $this->urgent,
            'estimate_to' => $to,
        ];

        ServiceOrder::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'message' => trim(self::describe($details)."\n".$this->comment),
            'source' => 'calculator',
            'estimate' => $from,
            'details' => $details,
        ]);

        $this->sent = true;
    }

    /** Human-readable summary of calculator inputs (in the current locale). */
    public static function describe(array $d): string
    {
        $cfg = config('rennova.calculator');
        $parts = [
            __('Площадь').': '.$d['area'].' '.__('м²'),
            __($cfg['property'][$d['property']][0] ?? $d['property']),
        ];
        if (! empty($d['condition'])) {
            $parts[] = __($cfg['condition'][$d['condition']][0] ?? $d['condition']);
        }
        foreach ($d['extras'] ?? [] as $e) {
            $parts[] = '+ '.__($cfg['extras'][$e][0] ?? $e);
        }
        if (! empty($d['urgent'])) {
            $parts[] = __('Срочно');
        }

        return implode(' · ', $parts);
    }

    public function render()
    {
        return view('livewire.calculator', [
            'services' => $this->services(),
            'estimate' => $this->estimate(),
        ]);
    }
}
