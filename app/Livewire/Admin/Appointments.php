<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Записи на консультацию')]
class Appointments extends Component
{
    use WithPagination;

    #[Url]
    public string $when = 'upcoming';

    public function updatingWhen(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        abort_unless(array_key_exists($status, Appointment::STATUSES), 422);
        Appointment::findOrFail($id)->update(['status' => $status]);
    }

    public function delete(int $id): void
    {
        Appointment::findOrFail($id)->delete();
    }

    public function render()
    {
        $q = Appointment::with('service', 'user');
        $q = $this->when === 'upcoming'
            ? $q->whereDate('date', '>=', today())->orderBy('date')->orderBy('time')
            : $q->whereDate('date', '<', today())->orderByDesc('date')->orderByDesc('time');

        return view('livewire.admin.appointments', ['appointments' => $q->paginate(25)]);
    }
}
