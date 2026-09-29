<?php

namespace App\Livewire\Admin;

use App\Models\ClientProject;
use App\Models\ClientProjectStage;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ClientProjectShow extends Component
{
    public ClientProject $project;

    public array $info = [];

    public array $stages = [];

    public string $newStage = '';

    public string $updateBody = '';

    public string $updateImage = '';

    public function mount(ClientProject $project): void
    {
        $this->project = $project;
        $this->info = [
            'title' => $project->title, 'address' => (string) $project->address, 'status' => $project->status,
            'service_id' => $project->service_id, 'starts_on' => $project->starts_on?->toDateString() ?? '',
            'ends_on' => $project->ends_on?->toDateString() ?? '', 'manager' => (string) $project->manager, 'note' => (string) $project->note,
        ];
        $this->loadStages();
    }

    protected function loadStages(): void
    {
        $this->stages = $this->project->stages()->get()->mapWithKeys(fn ($s) => [$s->id => [
            'title' => $s->title, 'status' => $s->status, 'ends_on' => $s->ends_on?->toDateString() ?? '', 'note' => (string) $s->note,
        ]])->all();
    }

    public function saveInfo(): void
    {
        $data = $this->validate([
            'info.title' => 'required|string|max:160',
            'info.address' => 'nullable|string|max:255',
            'info.status' => 'required|in:'.implode(',', array_keys(ClientProject::STATUSES)),
            'info.service_id' => 'nullable|exists:services,id',
            'info.starts_on' => 'nullable|date',
            'info.ends_on' => 'nullable|date',
            'info.manager' => 'nullable|string|max:120',
            'info.note' => 'nullable|string|max:5000',
        ], [], ['info.title' => __('название')])['info'];

        $this->project->update(array_map(fn ($v) => $v === '' ? null : $v, $data));
        session()->flash('saved', __('Сохранено.'));
    }

    public function saveStage(int $id): void
    {
        $this->validate([
            "stages.$id.title" => 'required|string|max:160',
            "stages.$id.status" => 'required|in:'.implode(',', array_keys(ClientProjectStage::STATUSES)),
            "stages.$id.ends_on" => 'nullable|date',
            "stages.$id.note" => 'nullable|string|max:2000',
        ]);
        $this->stage($id)->update(array_map(fn ($v) => $v === '' ? null : $v, $this->stages[$id]));
    }

    public function setStageStatus(int $id, string $status): void
    {
        abort_unless(array_key_exists($status, ClientProjectStage::STATUSES), 422);
        $this->stage($id)->update(['status' => $status]);
        $this->stages[$id]['status'] = $status;
        if ($status !== 'pending' && $this->project->status === 'planning') {
            $this->project->update(['status' => 'active']);
            $this->info['status'] = 'active';
        }
    }

    public function addStage(): void
    {
        $this->validate(['newStage' => 'required|string|max:160'], [], ['newStage' => __('этап')]);
        $this->project->stages()->create(['title' => $this->newStage, 'sort' => (int) $this->project->stages()->max('sort') + 1]);
        $this->newStage = '';
        $this->loadStages();
    }

    public function moveStage(int $id, int $dir): void
    {
        $ids = array_keys($this->stages);
        $i = array_search($id, $ids, true);
        $j = $i + $dir;
        if ($i === false || ! isset($ids[$j])) {
            return;
        }
        [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]];
        foreach ($ids as $sort => $sid) {
            $this->stage($sid)->update(['sort' => $sort]);
        }
        $this->loadStages();
    }

    public function deleteStage(int $id): void
    {
        $this->stage($id)->delete();
        $this->loadStages();
    }

    public function postUpdate(): void
    {
        $this->validate([
            'updateBody' => 'required|string|max:5000',
            'updateImage' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/media/)#'],
        ], ['regex' => __('Нужна ссылка https://… или загруженный файл.')], ['updateBody' => __('текст')]);
        $this->project->updates()->create(['body' => $this->updateBody, 'image' => $this->updateImage ?: null]);
        $this->reset('updateBody', 'updateImage');
    }

    public function deleteUpdate(int $id): void
    {
        $this->project->updates()->whereKey($id)->delete();
    }

    public function deleteProject()
    {
        $this->project->delete();

        return $this->redirectRoute('admin.client-projects', navigate: true);
    }

    protected function stage(int $id): ClientProjectStage
    {
        return $this->project->stages()->whereKey($id)->firstOrFail();
    }

    public function render()
    {
        $this->project->load('user', 'stages');

        return view('livewire.admin.client-project-show', [
            'services' => Service::orderBy('sort')->get(['id', 'title']),
            'updates' => $this->project->updates()->get(),
        ])->title($this->project->title);
    }
}
