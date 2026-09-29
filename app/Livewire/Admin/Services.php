<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Услуги')]
class Services extends Component
{
    public bool $open = false;

    public ?int $editingId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->form = [
            'title' => '', 'slug' => '', 'category' => 'renovation', 'excerpt' => '', 'description' => '',
            'features' => '', 'price_from' => null, 'price_unit' => 'м²', 'landmark' => 'eiffel',
            'is_bundle' => false, 'is_active' => true, 'sort' => 0,
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->open = true;
    }

    public function edit(int $id): void
    {
        $s = Service::findOrFail($id);
        $this->editingId = $s->id;
        $this->form = $s->only(array_keys($this->form));
        $this->form['features'] = implode("\n", $s->features ?? []);
        $this->open = true;
    }

    public function save(): void
    {
        $this->form['slug'] = Str::slug($this->form['slug'] ?: $this->form['title']);

        $data = $this->validate([
            'form.title' => 'required|string|max:160',
            'form.slug' => ['required', 'string', 'max:160', Rule::unique('services', 'slug')->ignore($this->editingId)],
            'form.category' => 'required|in:'.implode(',', array_keys(Service::CATEGORIES)),
            'form.excerpt' => 'nullable|string|max:500',
            'form.description' => 'nullable|string|max:10000',
            'form.features' => 'nullable|string|max:5000',
            'form.price_from' => 'nullable|integer|min:0',
            'form.price_unit' => 'nullable|string|max:40',
            'form.landmark' => 'nullable|in:'.implode(',', array_keys(config('rennova.landmarks'))),
            'form.is_bundle' => 'boolean',
            'form.is_active' => 'boolean',
            'form.sort' => 'integer',
        ], [], ['form.title' => 'название', 'form.slug' => 'адрес'])['form'];

        $data['features'] = array_values(array_filter(array_map('trim', explode("\n", $data['features'] ?? ''))));
        $data['is_bundle'] = $data['is_bundle'] || $data['category'] === 'bundle';

        Service::updateOrCreate(['id' => $this->editingId], $data);
        $this->open = false;
    }

    public function toggle(int $id): void
    {
        $s = Service::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
    }

    public function delete(int $id): void
    {
        Service::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.services', [
            'services' => Service::orderBy('sort')->orderBy('id')->get(),
        ]);
    }
}
