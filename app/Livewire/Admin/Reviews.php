<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Отзывы')]
class Reviews extends Component
{
    use WithPagination;

    #[Url]
    public string $status = 'pending';

    public array $replies = [];

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        abort_unless(array_key_exists($status, Review::STATUSES), 422);
        Review::findOrFail($id)->update(['status' => $status]);
    }

    public function saveReply(int $id): void
    {
        $this->validate(["replies.$id" => 'nullable|string|max:2000']);
        Review::findOrFail($id)->update(['reply' => trim($this->replies[$id] ?? '') ?: null]);
        session()->flash('saved-'.$id, true);
    }

    public function delete(int $id): void
    {
        Review::findOrFail($id)->delete();
    }

    public function render()
    {
        $reviews = Review::with('service', 'user')
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest()->paginate(20);
        foreach ($reviews as $r) {
            $this->replies[$r->id] ??= (string) $r->reply;
        }

        return view('livewire.admin.reviews', ['reviews' => $reviews]);
    }
}
