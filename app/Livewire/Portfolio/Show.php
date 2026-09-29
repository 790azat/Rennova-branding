<?php

namespace App\Livewire\Portfolio;

use App\Models\PortfolioProject;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public PortfolioProject $project;

    public function mount(PortfolioProject $project): void
    {
        abort_unless($project->is_published || auth()->user()?->isAdmin(), 404);
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.portfolio.show', [
            'related' => PortfolioProject::query()->published()->whereKeyNot($this->project->id)
                ->where('category', $this->project->category)->take(3)->get(),
        ])->title($this->project->tr('title'));
    }
}
