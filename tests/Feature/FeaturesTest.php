<?php

namespace Tests\Feature;

use App\Livewire;
use App\Models\Appointment;
use App\Models\Brand;
use App\Models\ClientProject;
use App\Models\PortfolioProject;
use App\Models\Post;
use App\Models\Review;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire as LivewireTest;
use Tests\TestCase;

class FeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept-Language', 'ru');
    }

    private function admin(): User
    {
        return User::where('role', 'admin')->first();
    }

    public function test_new_public_pages_render_in_every_language(): void
    {
        PortfolioProject::create(['slug' => 'test-flat', 'title' => 'Квартира на Северном', 'category' => 'renovation', 'is_published' => true, 'is_featured' => true]);
        $brand = Brand::first();
        $post = Post::published()->first();

        $pages = ['/portfolio', '/portfolio/test-flat', '/calculator', '/booking', '/reviews', '/contacts', '/blog', '/blog/'.$post->slug, '/brands/'.$brand->slug, '/sitemap.xml'];
        foreach (['ru', 'en', 'hy'] as $locale) {
            $this->get('/lang/'.$locale);
            foreach ($pages as $url) {
                $this->get($url)->assertOk();
            }
        }
        $this->get('/lang/ru');
        $this->get('/')->assertSee('Квартира на Северном')->assertSee($post->title);
        $this->get('/brands/'.$brand->slug)->assertSee('Запросить импорт');
        $this->get('/sitemap.xml')->assertSee('/blog/'.$post->slug);
    }

    public function test_unpublished_content_is_hidden(): void
    {
        PortfolioProject::create(['slug' => 'draft', 'title' => 'Черновик', 'category' => 'design', 'is_published' => false]);
        Post::create(['slug' => 'draft-post', 'title' => 'Черновик статьи', 'body' => 'Текст']);

        $this->get('/portfolio/draft')->assertNotFound();
        $this->get('/blog/draft-post')->assertNotFound();
        $this->actingAs($this->admin())->get('/portfolio/draft')->assertOk();
    }

    public function test_calculator_sends_estimate_to_admin(): void
    {
        LivewireTest::test(Livewire\Calculator::class)
            ->set('serviceSlug', 'remont-pod-klyuch')
            ->set('area', 80)
            ->set('name', 'Анна')
            ->set('phone', '+374 91 000000')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $order = ServiceOrder::latest('id')->first();
        $this->assertSame('calculator', $order->source);
        $this->assertGreaterThan(0, $order->estimate);
        $this->assertSame(80, (int) $order->details['area']);

        $this->actingAs($this->admin())->get('/admin/orders')->assertSee('Калькулятор');
    }

    public function test_booking_prevents_double_booking(): void
    {
        $component = LivewireTest::test(Livewire\Booking::class);
        $date = array_key_first($component->instance()->days());

        $component->set('date', $date)->set('time', '12:00')->set('name', 'Арам')->set('phone', '+37491111111')
            ->call('book')->assertHasNoErrors();
        $this->assertSame(1, Appointment::count());

        LivewireTest::test(Livewire\Booking::class)
            ->set('date', $date)->set('time', '12:00')->set('name', 'Гоар')->set('phone', '+37491222222')
            ->call('book')->assertHasErrors('time');
        $this->assertSame(1, Appointment::count());
    }

    public function test_reviews_are_moderated(): void
    {
        $user = User::factory()->create(['name' => 'Лилит']);
        LivewireTest::actingAs($user)->test(Livewire\Reviews::class)
            ->set('rating', 4)->set('body', 'Отличная команда, всё сделали в срок.')
            ->call('submit')->assertHasNoErrors();

        $review = Review::first();
        $this->assertSame('pending', $review->status);
        $this->get('/reviews')->assertSee('На модерации');
        auth()->logout();
        $this->get('/reviews')->assertDontSee('Отличная команда');

        LivewireTest::actingAs($this->admin())->test(Livewire\Admin\Reviews::class)->call('setStatus', $review->id, 'approved');
        auth()->logout();
        $this->get('/reviews')->assertSee('Отличная команда');
        $this->get('/')->assertSee('Отличная команда');
    }

    public function test_cabinet_shows_only_own_projects(): void
    {
        $client = User::factory()->create();
        $other = User::factory()->create();

        LivewireTest::actingAs($this->admin())->test(Livewire\Admin\ClientProjects::class)
            ->set('form.user_id', $client->id)->set('form.title', 'Квартира на Арами')
            ->call('save')->assertHasNoErrors();
        $project = ClientProject::first();
        $this->assertGreaterThan(0, $project->stages()->count());
        auth()->logout();

        $this->get('/my-projects')->assertRedirect('/login');
        $this->actingAs($client)->get('/my-projects')->assertSee('Квартира на Арами');
        $this->actingAs($client)->get('/my-projects/'.$project->id)->assertOk()->assertSee($project->stages()->first()->title);
        $this->actingAs($other)->get('/my-projects/'.$project->id)->assertForbidden();
    }

    public function test_new_admin_pages_and_media_upload(): void
    {
        $this->actingAs(User::factory()->create())->postJson('/admin/media', ['image' => 'x'])->assertForbidden();

        $admin = $this->admin();
        foreach (['/portfolio', '/products', '/appointments', '/reviews', '/posts', '/client-projects'] as $path) {
            $this->actingAs($admin)->get('/admin'.$path)->assertOk();
        }

        $png = base64_encode(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='));
        $url = $this->actingAs($admin)->postJson('/admin/media', ['image' => 'data:image/png;base64,'.$png])->assertOk()->json('url');
        $this->assertMatchesRegularExpression('#^/media/\d+\.png$#', $url);
        $this->get($url)->assertOk()->assertHeader('Content-Type', 'image/png');

        $this->actingAs($admin)->postJson('/admin/media', ['image' => 'data:text/plain;base64,aGVsbG8='])->assertStatus(422);
    }
}
