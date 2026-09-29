<?php

namespace App\Livewire\Admin;

use App\Models\ClientProject;
use App\Models\Service;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Проекты клиентов')]
class ClientProjects extends Component
{
    #[Url(as: 'user')]
    public ?int $prefillUser = null;

    public bool $open = false;

    public array $form = [];

    public function mount(): void
    {
        $this->form = ['user_id' => $this->prefillUser, 'service_id' => null, 'title' => '', 'address' => '', 'starts_on' => '', 'ends_on' => '', 'manager' => auth()->user()->name];
        $this->open = (bool) $this->prefillUser;
    }

    public function create(): void
    {
        $this->open = true;
    }

    public function save()
    {
        $data = $this->validate([
            'form.user_id' => 'required|exists:users,id',
            'form.service_id' => 'nullable|exists:services,id',
            'form.title' => 'required|string|max:160',
            'form.address' => 'nullable|string|max:255',
            'form.starts_on' => 'nullable|date',
            'form.ends_on' => 'nullable|date|after_or_equal:form.starts_on',
            'form.manager' => 'nullable|string|max:120',
        ], [], ['form.user_id' => __('клиент'), 'form.title' => __('название')])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $project = ClientProject::create($data);

        $category = $project->service?->category ?? 'renovation';
        foreach (ClientProject::STAGE_TEMPLATES[$category] ?? [] as $i => $title) {
            $project->stages()->create(['title' => __($title), 'sort' => $i]);
        }

        return $this->redirectRoute('admin.client-projects.show', $project, navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.client-projects', [
            'projects' => ClientProject::with('user', 'service', 'stages')->latest()->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'services' => Service::orderBy('sort')->get(['id', 'title']),
        ]);
    }
}
