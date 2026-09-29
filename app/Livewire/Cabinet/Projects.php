<?php

namespace App\Livewire\Cabinet;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Мои проекты')]
class Projects extends Component
{
    public function render()
    {
        return view('livewire.cabinet.projects', [
            'projects' => auth()->user()->clientProjects()->with('stages', 'service')->latest()->get(),
            'appointments' => auth()->user()->appointments()->with('service')
                ->whereDate('date', '>=', today())->where('status', '!=', 'cancelled')->orderBy('date')->orderBy('time')->get(),
        ]);
    }
}
