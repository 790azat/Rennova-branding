<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\ImportRequest;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Обзор')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'kpis' => [
                [__('Новые заявки на услуги'), ServiceOrder::where('status', 'new')->count()],
                [__('Новые запросы на импорт'), ImportRequest::where('status', 'new')->count()],
                [__('Пользователи'), User::count()],
                [__('Обсуждения / ответы'), Discussion::count().' / '.DiscussionReply::count()],
            ],
            'orders' => ServiceOrder::with('service')->latest()->take(6)->get(),
            'imports' => ImportRequest::with('user')->latest()->take(6)->get(),
            'brandCount' => Brand::count(),
        ]);
    }
}
