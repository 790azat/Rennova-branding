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

    public function test_setup_route_is_disabled_without_token(): void
    {
        $this->get('/setup/anything')->assertNotFound();
    }
}
