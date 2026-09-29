<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Бренды')]
class Brands extends Component
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
            'name' => '', 'country' => '', 'category' => '', 'tagline' => '', 'description' => '',
            'website' => '', 'logo_url' => '', 'is_exclusive' => true, 'is_featured' => false, 'is_active' => true, 'sort' => 0,
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
        $b = Brand::findOrFail($id);
        $this->editingId = $b->id;
        $this->form = array_map(fn ($v) => $v ?? '', $b->only(array_keys($this->form)));
        $this->open = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'form.name' => 'required|string|max:120',
            'form.country' => 'nullable|string|max:80',
            'form.category' => 'nullable|string|max:80',
            'form.tagline' => 'nullable|string|max:160',
            'form.description' => 'nullable|string|max:3000',
            'form.website' => 'nullable|url|max:255',
            'form.logo_url' => 'nullable|url|max:500',
            'form.is_exclusive' => 'boolean',
            'form.is_featured' => 'boolean',
            'form.is_active' => 'boolean',
            'form.sort' => 'integer',
        ], [], ['form.name' => 'название', 'form.website' => 'сайт', 'form.logo_url' => 'логотип'])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $data['slug'] = Str::slug($data['name']);

        validator(['slug' => $data['slug']], [
            'slug' => [Rule::unique('brands', 'slug')->ignore($this->editingId)],
        ], ['slug.unique' => 'Бренд с таким названием уже есть.'])->validate();

        Brand::updateOrCreate(['id' => $this->editingId], $data);
        $this->open = false;
    }

    public function toggle(int $id, string $field): void
    {
        abort_unless(in_array($field, ['is_active', 'is_exclusive', 'is_featured']), 422);
        $b = Brand::findOrFail($id);
        $b->update([$field => ! $b->{$field}]);
    }

    public function delete(int $id): void
    {
        Brand::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.brands', ['brands' => Brand::orderBy('sort')->orderBy('name')->get()]);
    }
}
