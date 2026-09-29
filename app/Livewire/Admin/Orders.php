<?php

namespace App\Livewire\Admin;

use App\Models\ServiceOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Заявки на услуги')]
class Orders extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        abort_unless(array_key_exists($status, ServiceOrder::STATUSES), 422);
        ServiceOrder::findOrFail($id)->update(['status' => $status]);
    }

    public function delete(int $id): void
    {
        ServiceOrder::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.orders', [
            'orders' => ServiceOrder::with('service', 'user')
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->latest()->paginate(20),
        ]);
    }
}
