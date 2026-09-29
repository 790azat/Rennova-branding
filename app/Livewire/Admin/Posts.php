<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\EditsTranslations;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Блог')]
class Posts extends Component
{
    use EditsTranslations;

    public bool $open = false;

    public ?int $editingId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->resetForm();
    }

    protected function translatableFields(): array
    {
        return ['title' => 'Заголовок', 'excerpt' => 'Анонс (для списка и поисковиков)', 'body' => 'Текст'];
    }

    protected function resetForm(): void
    {
        $this->form = [
            'title' => '', 'slug' => '', 'category' => 'trends', 'excerpt' => '', 'body' => '', 'cover_image' => '',
            'landmark' => 'eiffel', 'published_at' => now()->format('Y-m-d\TH:i'), 'is_published' => false,
        ];
        $this->fillTranslations();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->open = true;
    }

    public function edit(int $id): void
    {
        $p = Post::findOrFail($id);
        $this->editingId = $p->id;
        $this->form = [
            'title' => $p->title, 'slug' => $p->slug, 'category' => (string) $p->category, 'excerpt' => (string) $p->excerpt,
            'body' => $p->body, 'cover_image' => (string) $p->cover_image, 'landmark' => (string) $p->landmark,
            'published_at' => ($p->published_at ?? now())->format('Y-m-d\TH:i'), 'is_published' => $p->published_at !== null,
        ];
        $this->fillTranslations($p);
        $this->open = true;
    }

    public function save(): void
    {
        $this->form['slug'] = Str::slug($this->form['slug'] ?: $this->form['title']);

        $data = $this->validate([
            'form.title' => 'required|string|max:200',
            'form.slug' => ['required', 'string', 'max:200', Rule::unique('posts', 'slug')->ignore($this->editingId)],
            'form.category' => 'nullable|in:'.implode(',', array_keys(Post::CATEGORIES)),
            'form.excerpt' => 'nullable|string|max:500',
            'form.body' => 'required|string|max:100000',
            'form.cover_image' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/media/)#'],
            'form.landmark' => 'nullable|in:'.implode(',', array_keys(config('rennova.landmarks'))),
            'form.published_at' => 'nullable|date',
            'form.is_published' => 'boolean',
        ], ['regex' => __('Нужна ссылка https://… или загруженный файл.')], ['form.title' => __('заголовок'), 'form.body' => __('текст')])['form'];

        $published = $data['is_published'] ? Carbon::parse($data['published_at'] ?: now()) : null;
        unset($data['is_published']);
        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $data['published_at'] = $published;
        $data['translations'] = $this->translationsPayload();
        if (! $this->editingId) {
            $data['author_id'] = auth()->id();
        }

        Post::updateOrCreate(['id' => $this->editingId], $data);
        $this->open = false;
    }

    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.posts', ['posts' => Post::orderByDesc('published_at')->orderByDesc('id')->get()]);
    }
}
