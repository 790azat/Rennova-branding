<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Блог')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $category = '';

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.blog.index', [
            'categories' => array_intersect_key(Post::CATEGORIES, Post::query()->published()->pluck('category')->filter()->flip()->all()),
            'posts' => Post::query()->published()
                ->when($this->category !== '', fn ($q) => $q->where('category', $this->category))
                ->paginate(9),
        ]);
    }
}
