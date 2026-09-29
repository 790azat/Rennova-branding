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
        $all = Brand::query()->active()->withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();

        return view('livewire.brands', [
            'categories' => $all->filter(fn ($b) => filled($b->category))->unique('category')
                ->mapWithKeys(fn ($b) => [$b->category => $b->tr('category')])->sort(),
            'brands' => $all
                ->when($this->category !== '', fn ($c) => $c->where('category', $this->category))
                ->when($this->exclusive, fn ($c) => $c->where('is_exclusive', true)),
        ]);
    }
}
