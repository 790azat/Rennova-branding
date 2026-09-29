<?php

namespace App\Livewire;

use App\Models\Review;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Отзывы клиентов')]
class Reviews extends Component
{
    use WithPagination;

    public int $rating = 5;

    public ?int $serviceId = null;

    public string $body = '';

    public bool $sent = false;

    public function submit(): void
    {
        abort_unless(auth()->check(), 403);

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'serviceId' => 'nullable|exists:services,id',
            'body' => 'required|string|min:10|max:3000',
        ], [], ['rating' => __('оценка'), 'body' => __('отзыв')]);

        if (auth()->user()->reviews()->where('created_at', '>', now()->subMinutes(10))->exists()) {
            $this->addError('body', __('Вы только что оставили отзыв. Попробуйте чуть позже.'));

            return;
        }

        auth()->user()->reviews()->create([
            'name' => auth()->user()->name,
            'rating' => $this->rating,
            'service_id' => $this->serviceId,
            'body' => $this->body,
        ]);

        $this->reset('body', 'serviceId');
        $this->rating = 5;
        $this->sent = true;
    }

    public function render()
    {
        $approved = Review::query()->where('status', 'approved');

        return view('livewire.reviews', [
            'reviews' => Review::query()->approved()->with('service')->paginate(12),
            'average' => round((float) $approved->avg('rating'), 1),
            'count' => $approved->count(),
            'services' => Service::query()->active()->get(['id', 'title', 'translations']),
            'mine' => auth()->check() ? auth()->user()->reviews()->where('status', '!=', 'approved')->latest()->get() : collect(),
        ]);
    }
}
