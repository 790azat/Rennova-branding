<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless($post->isPublished() || auth()->user()?->isAdmin(), 404);
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.blog.show', [
            'more' => Post::query()->published()->whereKeyNot($this->post->id)->take(3)->get(),
        ])->title($this->post->tr('title'))->layoutData(['description' => $this->post->tr('excerpt')]);
    }
}
