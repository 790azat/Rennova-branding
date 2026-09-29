<?php

namespace App\Livewire\Admin;

use App\Models\ImportRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Запросы на импорт')]
class Imports extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    public ?int $editingId = null;

    public string $editStatus = 'new';

    public string $note = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $r = ImportRequest::findOrFail($id);
        $this->editingId = $r->id;
        $this->editStatus = $r->status;
        $this->note = (string) $r->admin_note;
    }

    public function save(): void
    {
        $this->validate([
            'editStatus' => 'required|in:'.implode(',', array_keys(ImportRequest::STATUSES)),
            'note' => 'nullable|string|max:3000',
        ]);
        ImportRequest::findOrFail($this->editingId)->update(['status' => $this->editStatus, 'admin_note' => $this->note ?: null]);
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        ImportRequest::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.imports', [
            'requests' => ImportRequest::with('user')
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->latest()->paginate(20),
        ]);
    }
}
