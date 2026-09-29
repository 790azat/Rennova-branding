<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\EditsTranslations;
use App\Models\PortfolioProject;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Портфолио')]
class Portfolio extends Component
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
        return ['title' => 'Название', 'summary' => 'Кратко', 'description' => 'Описание', 'location' => 'Где', 'duration' => 'Срок'];
    }

    protected function resetForm(): void
    {
        $this->form = [
            'title' => '', 'slug' => '', 'category' => 'renovation', 'service_id' => null, 'location' => '', 'year' => (int) date('Y'),
            'area' => null, 'duration' => '', 'summary' => '', 'description' => '', 'cover_image' => '', 'before_image' => '',
            'after_image' => '', 'gallery' => '', 'landmark' => 'cascade', 'is_featured' => false, 'is_published' => false, 'sort' => 0,
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
        $p = PortfolioProject::findOrFail($id);
        $this->editingId = $p->id;
        $this->form = array_map(fn ($v) => $v ?? '', $p->only(array_keys($this->form)));
        $this->form['service_id'] = $p->service_id;
        $this->form['area'] = $p->area;
        $this->form['year'] = $p->year;
        $this->form['gallery'] = implode("\n", $p->gallery ?? []);
        $this->fillTranslations($p);
        $this->open = true;
    }

    public function save(): void
    {
        $this->form['slug'] = Str::slug($this->form['slug'] ?: $this->form['title']);
        $image = ['nullable', 'string', 'max:500', 'regex:#^(https?://|/media/)#'];

        $data = $this->validate([
            'form.title' => 'required|string|max:160',
            'form.slug' => ['required', 'string', 'max:160', Rule::unique('portfolio_projects', 'slug')->ignore($this->editingId)],
            'form.category' => 'required|in:'.implode(',', array_keys(Service::CATEGORIES)),
            'form.service_id' => 'nullable|exists:services,id',
            'form.location' => 'nullable|string|max:160',
            'form.year' => 'nullable|integer|min:1990|max:2100',
            'form.area' => 'nullable|integer|min:1|max:1000000',
            'form.duration' => 'nullable|string|max:80',
            'form.summary' => 'nullable|string|max:500',
            'form.description' => 'nullable|string|max:10000',
            'form.cover_image' => $image,
            'form.before_image' => $image,
            'form.after_image' => $image,
            'form.gallery' => 'nullable|string|max:10000',
            'form.landmark' => 'nullable|in:'.implode(',', array_keys(config('rennova.landmarks'))),
            'form.is_featured' => 'boolean',
            'form.is_published' => 'boolean',
            'form.sort' => 'integer',
        ], ['regex' => __('Нужна ссылка https://… или загруженный файл.')], ['form.title' => __('название'), 'form.slug' => __('адрес')])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $data['gallery'] = array_values(array_filter(preg_split('/\s+/', (string) $data['gallery']),
            fn ($u) => preg_match('#^(https?://|/media/)#', $u)));
        $data['translations'] = $this->translationsPayload();

        PortfolioProject::updateOrCreate(['id' => $this->editingId], $data);
        $this->open = false;
    }

    public function toggle(int $id, string $field): void
    {
        abort_unless(in_array($field, ['is_published', 'is_featured'], true), 422);
        $p = PortfolioProject::findOrFail($id);
        $p->update([$field => ! $p->{$field}]);
    }

    public function delete(int $id): void
    {
        PortfolioProject::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.portfolio', [
            'projects' => PortfolioProject::orderBy('sort')->orderByDesc('id')->get(),
            'services' => Service::orderBy('sort')->get(['id', 'title']),
        ]);
    }
}
