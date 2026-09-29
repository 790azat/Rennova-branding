<?php

namespace App\Livewire\Admin;

use App\Models\Discussion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Обсуждения')]
class Discussions extends Component
{
    use WithPagination;

    public function toggle(int $id, string $field): void
    {
        abort_unless(in_array($field, ['is_pinned', 'is_closed']), 422);
        $d = Discussion::findOrFail($id);
        $d->update([$field => ! $d->{$field}]);
    }

    public function delete(int $id): void
    {
        Discussion::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.discussions', [
            'discussions' => Discussion::with('user')->withCount('replies')
                ->orderByDesc('is_pinned')->latest('last_activity_at')->paginate(25),
        ]);
    }
}
