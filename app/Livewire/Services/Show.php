<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Service $service;

    public function mount(Service $service): void
    {
        abort_unless($service->is_active, 404);
        $this->service = $service;
    }

    public function render()
    {
        return view('livewire.services.show', [
            'related' => Service::query()->active()
                ->whereKeyNot($this->service->id)
                ->where(fn ($q) => $q->where('category', $this->service->category)->orWhere('is_bundle', true))
                ->take(3)->get(),
        ])->title($this->service->title);
    }
}
