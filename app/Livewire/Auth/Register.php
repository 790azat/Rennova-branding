<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Регистрация')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160|unique:users,email',
            'phone' => 'nullable|string|max:40',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], ['name' => __('имя'), 'email' => 'email', 'phone' => __('телефон'), 'password' => __('пароль')]);

        $user = User::query()->create($data);
        Auth::login($user, true);
        session()->regenerate();

        $this->redirectRoute('home');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
