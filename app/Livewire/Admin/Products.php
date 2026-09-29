<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\EditsTranslations;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Товары брендов')]
class Products extends Component
{
    use EditsTranslations;

    #[Url]
    public ?int $brand = null;

    public bool $open = false;

    public ?int $editingId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->resetForm();
    }

    protected function translatableFields(): array
    {
        return ['name' => 'Название', 'category' => 'Категория', 'description' => 'Описание', 'price_note' => 'Цена / условия'];
    }

    protected function resetForm(): void
    {
        $this->form = [
            'brand_id' => $this->brand ?? Brand::orderBy('sort')->value('id'), 'name' => '', 'category' => '',
            'description' => '', 'image' => '', 'price_note' => '', 'is_active' => true, 'sort' => 0,
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
        $p = Product::findOrFail($id);
        $this->editingId = $p->id;
        $this->form = array_map(fn ($v) => $v ?? '', $p->only(array_keys($this->form)));
        $this->fillTranslations($p);
        $this->open = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'form.brand_id' => 'required|exists:brands,id',
            'form.name' => 'required|string|max:160',
            'form.category' => 'nullable|string|max:80',
            'form.description' => 'nullable|string|max:3000',
            'form.image' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/media/)#'],
            'form.price_note' => 'nullable|string|max:120',
            'form.is_active' => 'boolean',
            'form.sort' => 'integer',
        ], ['regex' => __('Нужна ссылка https://… или загруженный файл.')], ['form.name' => __('название')])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $data['slug'] = Str::slug($data['name']) ?: Str::random(8);
        validator(['slug' => $data['slug']], [
            'slug' => [Rule::unique('products', 'slug')->where('brand_id', $data['brand_id'])->ignore($this->editingId)],
        ], ['slug.unique' => __('У этого бренда уже есть товар с таким названием.')])->validate();
        $data['translations'] = $this->translationsPayload();

        Product::updateOrCreate(['id' => $this->editingId], $data);
        $this->open = false;
    }

    public function toggle(int $id): void
    {
        $p = Product::findOrFail($id);
        $p->update(['is_active' => ! $p->is_active]);
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.products', [
            'brands' => Brand::orderBy('sort')->orderBy('name')->get(['id', 'name', 'slug']),
            'products' => Product::with('brand')->when($this->brand, fn ($q) => $q->where('brand_id', $this->brand))
                ->orderBy('brand_id')->orderBy('sort')->orderBy('name')->get(),
        ]);
    }
}
