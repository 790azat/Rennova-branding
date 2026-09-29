<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Вход')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = true;

    protected function validationAttributes(): array
    {
        return ['email' => 'email', 'password' => __('пароль')];
    }

    public function login(): void
    {
        $this->validate();

        $key = Str::lower($this->email).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', __('Слишком много попыток. Повторите через :seconds сек.', ['seconds' => RateLimiter::availableIn($key)]));

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key);
            $this->addError('email', __('Неверный email или пароль.'));

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        $this->redirectIntended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('home'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
