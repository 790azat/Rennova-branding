<?php

namespace App\Livewire\Discussions;

use App\Models\Discussion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Обсуждения')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $category = '';

    #[Url(as: 'q')]
    public string $search = '';

    public bool $creating = false;

    public string $newCategory = 'design';

    public string $newTitle = '';

    public string $newBody = '';

    public function updating($name): void
    {
        if (in_array($name, ['category', 'search'])) {
            $this->resetPage();
        }
    }

    public function startCreating(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login');

            return;
        }
        $this->creating = true;
    }

    public function create(): void
    {
        abort_unless(auth()->check(), 403);

        $data = $this->validate([
            'newCategory' => 'required|in:'.implode(',', array_keys(Discussion::CATEGORIES)),
            'newTitle' => 'required|string|min:5|max:160',
            'newBody' => 'required|string|min:10|max:5000',
        ], [], ['newCategory' => __('раздел'), 'newTitle' => __('заголовок'), 'newBody' => __('текст')]);

        $discussion = auth()->user()->discussions()->create([
            'category' => $data['newCategory'],
            'title' => $data['newTitle'],
            'body' => $data['newBody'],
            'last_activity_at' => now(),
        ]);

        $this->redirectRoute('discussions.show', $discussion, navigate: true);
    }

    public function render()
    {
        $discussions = Discussion::query()
            ->with('user')->withCount('replies')
            ->when(array_key_exists($this->category, Discussion::CATEGORIES), fn ($q) => $q->where('category', $this->category))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('body', 'like', '%'.$this->search.'%')))
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_activity_at')
            ->paginate(15);

        return view('livewire.discussions.index', compact('discussions'));
    }
}
