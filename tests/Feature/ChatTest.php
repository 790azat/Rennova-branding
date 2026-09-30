<?php

namespace Tests\Feature;

use App\Livewire;
use App\Models\ChatConversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire as LivewireTest;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept-Language', 'ru');
    }

    public function test_visitor_and_admin_exchange_messages(): void
    {
        $this->get('/')->assertOk()->assertSee('Онлайн-чат');

        // A guest must give a name with the first message.
        $widget = LivewireTest::test(Livewire\ChatWidget::class)->call('openChat')
            ->call('send', 'Здравствуйте, сколько стоит ремонт?')->assertHasErrors('name');
        $this->assertSame(0, ChatConversation::count());

        $widget->set('name', 'Арам')->set('contact', '+37491000000')
            ->call('send', 'Здравствуйте, сколько стоит ремонт?')->assertHasNoErrors()->assertReturned(true);

        $conversation = ChatConversation::first();
        $this->assertSame('Арам', $conversation->name);
        $this->assertSame(1, $conversation->unread_admin);

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin)->get('/admin/chats')->assertOk()->assertSee('Арам');
        LivewireTest::actingAs($admin)->test(Livewire\Admin\Chats::class)
            ->call('select', $conversation->id)
            ->call('send', 'Добрый день! Зависит от площади.')->assertHasNoErrors();

        $conversation->refresh();
        $this->assertSame(0, $conversation->unread_admin);
        $this->assertSame(1, $conversation->unread_visitor);

        // The same visitor session sees the reply, and opening the chat marks it read.
        auth()->logout();
        $html = $widget->call('openChat')->html();
        $this->assertLessThan(strpos($html, 'Добрый день! Зависит от площади.'), strpos($html, 'Здравствуйте, сколько стоит ремонт?'));
        $this->assertSame(0, $conversation->fresh()->unread_visitor);
    }

    public function test_visitors_cannot_see_each_others_chats(): void
    {
        ChatConversation::create(['token' => str_repeat('a', 40), 'name' => 'Другой'])->post('visitor', 'Секретный вопрос');

        LivewireTest::test(Livewire\ChatWidget::class)->call('openChat')->assertDontSee('Секретный вопрос');
        $this->actingAs(User::factory()->create())->get('/admin/chats')->assertForbidden();
    }

    public function test_chat_is_rate_limited(): void
    {
        $widget = LivewireTest::test(Livewire\ChatWidget::class)->set('name', 'Гость');
        foreach (range(1, 8) as $i) {
            $widget->call('send', "Сообщение {$i}")->assertHasNoErrors();
        }
        $widget->call('send', 'Ещё одно')->assertHasErrors('body');
        $this->assertSame(8, ChatConversation::first()->messages()->count());
    }
}
