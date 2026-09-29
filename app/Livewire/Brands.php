<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Эксклюзивные бренды')]
class Brands extends Component
{
    #[Url]
    public string $category = '';

    #[Url]
    public bool $exclusive = false;

    public function render()
    {
        $all = Brand::query()->active()->get();

        return view('livewire.brands', [
            'categories' => $all->pluck('category')->filter()->unique()->sort()->values(),
            'brands' => $all
                ->when($this->category !== '', fn ($c) => $c->where('category', $this->category))
                ->when($this->exclusive, fn ($c) => $c->where('is_exclusive', true)),
        ]);
    }
}
