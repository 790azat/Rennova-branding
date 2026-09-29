<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ServiceRequestForm extends Component
{
    public ?int $serviceId = null;

    public bool $sent = false;

    #[Validate('required|string|max:120', as: 'имя')]
    public string $name = '';

    #[Validate('required|string|max:40', as: 'телефон')]
    public string $phone = '';

    #[Validate('nullable|email|max:160', as: 'email')]
    public string $email = '';

    #[Validate('nullable|string|max:3000', as: 'сообщение')]
    public string $message = '';

    public function mount(?int $serviceId = null): void
    {
        $this->serviceId = $serviceId;

        if ($user = auth()->user()) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = (string) $user->phone;
        }
    }

    public function submit(): void
    {
        $this->validate();
        $this->validate(['serviceId' => 'nullable|exists:services,id']);

        $key = 'service-request:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('name', 'Слишком много заявок. Попробуйте чуть позже.');

            return;
        }
        RateLimiter::hit($key, 600);

        ServiceOrder::query()->create([
            'user_id' => auth()->id(),
            'service_id' => $this->serviceId,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'message' => $this->message ?: null,
        ]);

        $this->reset('message');
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.service-request-form', [
            'services' => Service::query()->active()->get(['id', 'title']),
        ]);
    }
}
