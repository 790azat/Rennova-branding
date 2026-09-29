<?php

namespace App\Livewire\Portfolio;

use App\Models\PortfolioProject;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Портфолио')]
class Index extends Component
{
    #[Url]
    public string $category = '';

    public function render()
    {
        $all = PortfolioProject::query()->published()->get();

        return view('livewire.portfolio.index', [
            'categories' => array_intersect_key(Service::CATEGORIES, $all->pluck('category')->flip()->all()),
            'projects' => $this->category === '' ? $all : $all->where('category', $this->category),
        ]);
    }
}
