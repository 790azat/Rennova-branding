<?php

use App\Http\Controllers\MediaController;
use App\Livewire;
use App\Models\Brand;
use App\Models\PortfolioProject;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home');
Route::get('/lang/{locale}', function (Request $request, string $locale) {
    abort_unless(array_key_exists($locale, config('rennova.locales')), 404);
    $request->session()->put('locale', $locale);

    return redirect()->back(fallback: route('home'));
})->name('locale');
Route::get('/services', Livewire\Services\Index::class)->name('services.index');
Route::get('/services/{service:slug}', Livewire\Services\Show::class)->name('services.show');
Route::get('/brands', Livewire\Brands::class)->name('brands');
Route::get('/brands/{brand:slug}', Livewire\BrandShow::class)->name('brands.show');
Route::get('/portfolio', Livewire\Portfolio\Index::class)->name('portfolio');
Route::get('/portfolio/{project:slug}', Livewire\Portfolio\Show::class)->name('portfolio.show');
Route::get('/calculator', Livewire\Calculator::class)->name('calculator');
Route::get('/booking', Livewire\Booking::class)->name('booking');
Route::get('/reviews', Livewire\Reviews::class)->name('reviews');
Route::get('/contacts', Livewire\Contacts::class)->name('contacts');
Route::get('/blog', Livewire\Blog\Index::class)->name('blog');
Route::get('/blog/{post:slug}', Livewire\Blog\Show::class)->name('blog.show');
Route::get('/media/{id}.{ext}', [MediaController::class, 'show'])->whereNumber('id')->name('media.show');
Route::get('/sitemap.xml', function () {
    $urls = collect(['home', 'services.index', 'portfolio', 'brands', 'calculator', 'booking', 'blog', 'reviews', 'contacts', 'discussions.index', 'imports'])
        ->map(fn ($name) => route($name))
        ->merge(Service::where('is_active', true)->get()->map(fn ($s) => route('services.show', $s)))
        ->merge(PortfolioProject::published()->get()->map(fn ($p) => route('portfolio.show', $p)))
        ->merge(Post::published()->get()->map(fn ($p) => route('blog.show', $p)))
        ->merge(Brand::where('is_active', true)->get()->map(fn ($b) => route('brands.show', $b)));

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url><loc>'.e($url).'</loc></url>';
    }

    return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
Route::get('/discussions', Livewire\Discussions\Index::class)->name('discussions.index');
Route::get('/discussions/{discussion}', Livewire\Discussions\Show::class)->name('discussions.show');
Route::get('/imports', Livewire\Imports::class)->name('imports');

Route::middleware('guest')->group(function () {
    Route::get('/login', Livewire\Auth\Login::class)->name('login');
    Route::get('/register', Livewire\Auth\Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Livewire\Profile::class)->name('profile');
    Route::get('/my-projects', Livewire\Cabinet\Projects::class)->name('cabinet');
    Route::get('/my-projects/{project}', Livewire\Cabinet\ProjectShow::class)->name('cabinet.project');
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
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::get('/portfolio', Livewire\Admin\Portfolio::class)->name('portfolio');
    Route::get('/products', Livewire\Admin\Products::class)->name('products');
    Route::get('/appointments', Livewire\Admin\Appointments::class)->name('appointments');
    Route::get('/reviews', Livewire\Admin\Reviews::class)->name('reviews');
    Route::get('/posts', Livewire\Admin\Posts::class)->name('posts');
    Route::get('/chats', Livewire\Admin\Chats::class)->name('chats');
    Route::get('/client-projects', Livewire\Admin\ClientProjects::class)->name('client-projects');
    Route::get('/client-projects/{project}', Livewire\Admin\ClientProjectShow::class)->name('client-projects.show');
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
        $out = 'Error: '.$e->getMessage();
    }

    // Smoke check: render the main pages in every language and report failures.
    foreach (array_keys(config('rennova.locales')) as $locale) {
        $pages = [
            '/' => Livewire\Home::class,
            '/services' => Livewire\Services\Index::class,
            '/brands' => Livewire\Brands::class,
            '/discussions' => Livewire\Discussions\Index::class,
            '/portfolio' => Livewire\Portfolio\Index::class,
            '/calculator' => Livewire\Calculator::class,
            '/booking' => Livewire\Booking::class,
            '/reviews' => Livewire\Reviews::class,
            '/contacts' => Livewire\Contacts::class,
            '/blog' => Livewire\Blog\Index::class,
        ];
        foreach ($pages as $path => $component) {
            try {
                app()->setLocale($locale);
                \Livewire\Livewire::mount($component);
                $out .= "\nOK   {$locale} {$path}";
            } catch (Throwable $e) {
                $out .= "\nFAIL {$locale} {$path}: ".$e->getMessage().' @ '.basename($e->getFile()).':'.$e->getLine();
            }
        }
    }

    return response($out, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});
