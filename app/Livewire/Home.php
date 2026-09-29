<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Discussion;
use App\Models\PortfolioProject;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.home', [
            'services' => Service::query()->active()->where('is_bundle', false)->get()
                ->unique('category')->values(),
            'bundle' => Service::query()->active()->where('is_bundle', true)->first(),
            'brands' => Brand::query()->active()->get(),
            'discussions' => Discussion::query()->with('user')->withCount('replies')
                ->latest('last_activity_at')->take(3)->get(),
            'projects' => PortfolioProject::query()->published()->where('is_featured', true)->take(3)->get(),
            'reviews' => Review::query()->approved()->with('service')->latest()->take(3)->get(),
            'posts' => Post::query()->published()->take(3)->get(),
        ]);
    }
}
