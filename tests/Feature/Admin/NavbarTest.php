<?php

namespace Tests\Feature\Admin;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_view_the_users_dropdown($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/articles');

        // Assert
        $status->contains('admin')
            ? $response
                ->assertSeeText('Users')
                ->assertSeeText('Пользователи')
                ->assertSeeText('Подписчики')
            : $response
                ->assertDontSeeText('Users')
                ->assertDontSeeText('Пользователи')
                ->assertDontSeeText('Подписчики');
    }
}
