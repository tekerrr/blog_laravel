<?php

namespace Tests\Feature\Admin;

use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class SubscribersTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_view_the_subscriber_list_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $subscriber = factory(Subscriber::class)->create();

        // Act
        $response = $this->get('/admin/subscribers');

        // Assert
        $status->contains('admin')
            ? $response
                ->assertViewIs('admin.subscribers.index')
                ->assertSeeText('Подписчики')
                ->assertSeeText($subscriber->email)
            : $response
                ->assertDontSeeText('Подписчики');
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_destroy_the_subscriber($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $subscriber = factory(Subscriber::class)->create();

        // Act
       $this->delete('/admin/subscribers/' . $subscriber->email);

        // Assert
        $status->contains('admin')
            ? $this->assertDatabaseMissing(app(Subscriber::class)->getTable(), ['email' => $subscriber->email])
            : $this->assertDatabaseHas(app(Subscriber::class)->getTable(), ['email' => $subscriber->email]);
    }
}
