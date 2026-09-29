<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class BrandShow extends Component
{
    public Brand $brand;

    #[Url]
    public string $category = '';

    public function mount(Brand $brand): void
    {
        abort_unless($brand->is_active, 404);
        $this->brand = $brand;
    }

    public function render()
    {
        $products = $this->brand->products()->active()->get();

        return view('livewire.brand-show', [
            'categories' => $products->filter(fn ($p) => filled($p->category))->unique('category')
                ->mapWithKeys(fn ($p) => [$p->category => $p->tr('category')])->sort(),
            'products' => $this->category === '' ? $products : $products->where('category', $this->category),
        ])->title($this->brand->name);
    }
}
