<?php

namespace Tests\Feature\Common;

use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithRoles;

class SubscribersTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

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
    public function a_subscribed_user_can_view_the_subscribe_button_on_the_article_list_page()
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
        $this->post('/subscriber', $attributes);

        // Assert
        $this->assertDatabaseHas((new Subscriber())->getTable(), $attributes);
    }

    /** @test */
    public function a_unsubscribed_user_can_subscribe()
    {
        // Arrange
        $user = $this->actingAsUser();

        // Act
        $this->post('/subscriber');

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
        $this->delete('/subscriber');

        // Assert
        $this->assertFalse($user->isSubscriber());
    }

    /** @test */
    public function a_subscribed_user_doesnt_create_a_new_subscriber_when_subscribe()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->subscription()->create();

        // Act
        $this->post('/subscriber');

        // Assert
        $this->assertEquals(1, Subscriber::count());
    }
}
