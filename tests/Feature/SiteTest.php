<?php

namespace Tests\Feature;

use App\Models\Discussion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        // Symfony's test request sends "Accept-Language: en-us" by default; the site's default language is Russian.
        $this->withHeader('Accept-Language', 'ru');
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/services', '/services/rennova-complete', '/brands', '/discussions', '/imports', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/discussions/'.Discussion::first()->id)->assertOk();
        $this->get('/nope')->assertNotFound()->assertSee('Страница не найдена');
    }

    public function test_admin_is_protected(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();

        $admin = User::where('role', 'admin')->first();
        foreach (['', '/orders', '/imports', '/discussions', '/services', '/brands', '/users', '/settings'] as $path) {
            $this->actingAs($admin)->get('/admin'.$path)->assertOk();
        }
    }

    public function test_language_switch(): void
    {
        $this->get('/')->assertSee('Все услуги');
        $this->withHeader('Accept-Language', 'de-DE,de;q=0.9')->get('/')->assertSee('Все услуги');
        $this->withHeader('Accept-Language', 'en-US,en;q=0.9')->get('/')->assertSee('All services');

        $this->get('/lang/hy')->assertRedirect();
        $this->get('/')->assertSee('Բոլոր ծառայությունները')->assertSee('lang="hy"', false);

        $this->get('/lang/en');
        $this->get('/services/rennova-complete')->assertOk()->assertSee('The full turnkey cycle');
        $this->get('/lang/xx')->assertNotFound();
    }

    public function test_admin_edits_translations(): void
    {
        $admin = User::where('role', 'admin')->first();
        $service = \App\Models\Service::where('slug', 'rennova-complete')->first();

        \Livewire\Livewire::actingAs($admin)->test(\App\Livewire\Admin\Services::class)
            ->call('edit', $service->id)
            ->assertSet('tr.en.title', 'Rennova Complete')
            ->set('tr.hy.title', 'Նոր անվանում')
            ->set('tr.hy.features', "Մեկ\nԵրկու")
            ->call('save')
            ->assertHasNoErrors();

        $service->refresh();
        $this->assertSame('Նոր անվանում', $service->tr('title', 'hy'));
        $this->assertSame(['Մեկ', 'Երկու'], $service->tr('features', 'hy'));
        $this->assertSame('Rennova Complete', $service->tr('title', 'en'));
    }

    public function test_setup_route_is_disabled_without_token(): void
    {
        $this->get('/setup/anything')->assertNotFound();
    }
}
