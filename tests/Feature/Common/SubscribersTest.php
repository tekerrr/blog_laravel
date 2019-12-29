<?php

namespace Tests\Feature\Common;

use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_guest_can_view_the_subscribe_button_on_the_article_list_page()
    {
        // Act
        $response = $this->get('/articles');

        // Assert
        $response->assertSee('Подписаться на рассылку');
    }

    /** @test */
    public function a_unsubscribed_user_can_view_the_subscribe_button_on_the_article_list_page()
    {
        // Arrange
        $this->actingAsUser();

        // Act
        $response = $this->get('/articles');

        // Assert
        $response->assertSee('Подписаться на рассылку');
    }

    /** @test */
    public function a_subscribed_user_cannot_view_the_subscribe_button_on_the_article_list_page()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->subscription()->create();

        // Act
        $response = $this->get('/articles');

        // Assert
        $response->assertDontSee('Подписаться на рассылку');
    }

    /** @test */
    public function a_unsubscribed_user_can_view_the_subscribe_button_on_his_account_page()
    {
        // Arrange
        $this->actingAsUser();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee('Оформить');
    }

    /** @test */
    public function a_subscribed_user_can_view_the_unsubscribe_button_on_his_account_page()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->subscription()->create();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee('Отписаться');
    }

    /** @test */
    public function a_guest_can_subscribe()
    {
        // Arrange
        $attributes = ['email' => $this->faker->email];

        // Act
        $this->post('/subscribe', $attributes);

        // Assert
        $this->assertDatabaseHas((new Subscriber())->getTable(), $attributes);
    }

    /** @test */
    public function a_unsubscribed_user_can_subscribe()
    {
        // Arrange
        $user = $this->actingAsUser();

        // Act
        $this->post('/subscribe');

        // Assert
        $this->assertTrue($user->isSubscriber());
    }

    /** @test */
    public function a_subscribed_user_can_unsubscribe()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->subscription()->create();

        // Act
        $this->post('/unsubscribe');

        // Assert
        $this->assertFalse($user->isSubscriber());
    }

    /** @test */
    public function a_subscriber_can_unsubscribe_by_link()
    {
        // Arrange
        $subscriber = factory(Subscriber::class)->create();
        $link = \URL::signedRoute('unsubscribe.link', compact('subscriber'));

        // Act
        $this->get($link);

        // Assert
        $this->assertDatabaseMissing($subscriber->getTable(), ['email' => $subscriber->email]);
    }

    /** @test */
    public function a_subscriber_cannot_unsubscribe_by_link_without_signature()
    {
        // Arrange
        $subscriber = factory(Subscriber::class)->create();
        $link = route('unsubscribe.link', compact('subscriber'));

        // Act
        $response = $this->get($link);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas($subscriber->getTable(), ['email' => $subscriber->email]);
    }

    /** @test */
    public function a_subscribed_user_doesnt_create_a_new_subscriber_when_subscribe()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->subscription()->create();

        // Act
        $this->post('/subscribe');

        // Assert
        $this->assertEquals(1, Subscriber::count());
    }
}
