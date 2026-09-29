<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Пользователи')]
class Users extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAdmin(int $id): void
    {
        abort_if($id === auth()->id(), 422, __('Нельзя снять права с самого себя.'));
        $u = User::findOrFail($id);
        $u->update(['role' => $u->isAdmin() ? 'user' : 'admin']);
    }

    public function delete(int $id): void
    {
        abort_if($id === auth()->id(), 422);
        User::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::withCount('discussions', 'importRequests')
                ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")))
                ->latest()->paginate(25),
        ]);
    }
}
