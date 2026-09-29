<?php

namespace App\Livewire\Cabinet;

use App\Models\ClientProject;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProjectShow extends Component
{
    public ClientProject $project;

    public function mount(ClientProject $project): void
    {
        abort_unless($project->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        $this->project = $project->load('stages', 'updates', 'service');
    }

    public function render()
    {
        return view('livewire.cabinet.project-show')->title($this->project->title);
    }
}
