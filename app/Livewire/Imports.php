<?php

namespace App\Livewire;

use App\Models\ImportRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Импорт специальных товаров')]
class Imports extends Component
{
    #[Url(as: 'brand')]
    public string $preferredBrand = '';

    public string $productType = 'finishing';

    public string $title = '';

    public string $description = '';

    public string $quantity = '';

    public ?int $budget = null;

    public bool $sent = false;

    public function submit(): void
    {
        abort_unless(auth()->check(), 403);

        $data = $this->validate([
            'productType' => 'required|in:'.implode(',', array_keys(ImportRequest::PRODUCT_TYPES)),
            'title' => 'required|string|min:3|max:160',
            'description' => 'required|string|min:10|max:5000',
            'preferredBrand' => 'nullable|string|max:120',
            'quantity' => 'nullable|string|max:120',
            'budget' => 'nullable|integer|min:0|max:4000000000',
        ], [], [
            'productType' => __('тип товара'), 'title' => __('название'), 'description' => __('описание'),
            'preferredBrand' => __('бренд'), 'quantity' => __('количество'), 'budget' => __('бюджет'),
        ]);

        auth()->user()->importRequests()->create([
            'product_type' => $data['productType'],
            'title' => $data['title'],
            'description' => $data['description'],
            'preferred_brand' => $data['preferredBrand'] ?: null,
            'quantity' => $data['quantity'] ?: null,
            'budget' => $data['budget'],
        ]);

        $this->reset('title', 'description', 'quantity', 'budget', 'preferredBrand');
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.imports', [
            'requests' => auth()->check() ? auth()->user()->importRequests()->latest()->get() : collect(),
        ]);
    }
}
