<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Профиль')]
class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = (string) $user->phone;
    }

    public function save(): void
    {
        $user = auth()->user();
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:40',
        ], [], ['name' => __('имя'), 'phone' => __('телефон')]);

        $user->update($data);
        session()->flash('saved', __('Профиль сохранён.'));
    }

    public function changePassword(): void
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], ['current_password' => __('текущий пароль'), 'password' => __('новый пароль')]);

        auth()->user()->update(['password' => Hash::make($this->password)]);
        $this->reset('current_password', 'password', 'password_confirmation');
        session()->flash('saved', __('Пароль обновлён.'));
    }

    public function render()
    {
        return view('livewire.profile', [
            'orders' => auth()->user()->serviceOrders()->with('service')->latest()->get(),
            'discussions' => auth()->user()->discussions()->withCount('replies')->latest()->get(),
        ]);
    }
}
