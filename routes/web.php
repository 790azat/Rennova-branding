<?php

use App\Livewire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home');
Route::get('/services', Livewire\Services\Index::class)->name('services.index');
Route::get('/services/{service:slug}', Livewire\Services\Show::class)->name('services.show');
Route::get('/brands', Livewire\Brands::class)->name('brands');
Route::get('/discussions', Livewire\Discussions\Index::class)->name('discussions.index');
Route::get('/discussions/{discussion}', Livewire\Discussions\Show::class)->name('discussions.show');
Route::get('/imports', Livewire\Imports::class)->name('imports');

Route::middleware('guest')->group(function () {
    Route::get('/login', Livewire\Auth\Login::class)->name('login');
    Route::get('/register', Livewire\Auth\Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Livewire\Profile::class)->name('profile');
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    })->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/orders', Livewire\Admin\Orders::class)->name('orders');
    Route::get('/imports', Livewire\Admin\Imports::class)->name('imports');
    Route::get('/discussions', Livewire\Admin\Discussions::class)->name('discussions');
    Route::get('/services', Livewire\Admin\Services::class)->name('services');
    Route::get('/brands', Livewire\Admin\Brands::class)->name('brands');
    Route::get('/users', Livewire\Admin\Users::class)->name('users');
    Route::get('/settings', Livewire\Admin\Settings::class)->name('settings');
});

// One-time database setup for serverless hosting (Vercel), enabled only when SETUP_TOKEN is set.
Route::get('/setup/{token}', function (string $token) {
    $expected = config('rennova.setup_token');
    abort_unless(filled($expected) && hash_equals($expected, $token), 404);

    try {
        Artisan::call('migrate', ['--force' => true]);
        $out = Artisan::output();
        Artisan::call('db:seed', ['--force' => true]);
        $out .= Artisan::output();
    } catch (Throwable $e) {
        $out = 'Ошибка: '.$e->getMessage();
    }

    return response($out, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});
