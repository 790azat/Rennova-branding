<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Настройки')]
class Settings extends Component
{
    public array $values = [];

    public bool $saved = false;

    public function mount(): void
    {
        foreach (array_keys(Setting::FIELDS) as $key) {
            $this->values[$key] = Setting::query()->find($key)?->value ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'values.facebook_url' => 'nullable|url|max:255',
            'values.instagram_url' => 'nullable|url|max:255',
            'values.phone' => 'nullable|string|max:40',
            'values.email' => 'nullable|email|max:160',
            'values.address' => 'nullable|string|max:255',
        ], [], ['values.facebook_url' => 'Facebook', 'values.instagram_url' => 'Instagram']);

        foreach (array_keys(Setting::FIELDS) as $key) {
            Setting::put($key, $this->values[$key] ?? null);
        }
        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
