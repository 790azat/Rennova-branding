<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Услуги')]
class Index extends Component
{
    #[Url]
    public string $category = '';

    public function render()
    {
        $services = Service::query()->active()
            ->when(array_key_exists($this->category, Service::CATEGORIES), fn ($q) => $q->where('category', $this->category))
            ->get();

        return view('livewire.services.index', compact('services'));
    }
}
