<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithImage;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_view_the_user_list_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/users');

        // Assert
        $status->contains('admin')
            ? $response
                ->assertViewIs('admin.users.index')
                ->assertSeeText('Пользователи')
            : $response
                ->assertDontSeeText('Пользователи');
    }

    /** @test */
    public function the_user_list_page_shows_active_and_inactive_users()
    {
        // Arrange
        $this->actingAsRole('admin');
        $activeUser = $this->createUser(['is_active' => true]);
        $inactiveUser = $this->createUser(['is_active' => false]);

        // Act
        $response = $this->get('/admin/users');

        // Assert
        $response
            ->assertSeeText($activeUser->title)
            ->assertSeeText($inactiveUser->title);
    }

    /** @test */
    public function the_user_list_page_shows_user_roles()
    {
        // Arrange
        $this->actingAsRole('admin');
        $user = $this->createUser();
        $user->addRole($role = $this->faker->word);

        // Act
        $response = $this->get('/admin/users');

        // Assert
        $response->assertSeeText($role);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_view_the_user_editing_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser();

        // Act
        $response = $this->get('/admin/users/' . $user->id . '/edit');

        // Assert
        $status->contains('admin')
            ? $response
                ->assertViewIs('admin.users.edit')
                ->assertSeeText('Редактирование пользователя')
            : $response
                ->assertDontSeeText('Редактирование пользователя');
    }

    /** @test */
    public function the_user_editing_page_shows_user_attributes()
    {
        // Arrange
        $this->actingAsRole('admin');
        $user = $this->createUser();

        // Act
        $response = $this->get('/admin/users/' . $user->id . '/edit');

        // Assert
        $response
            ->assertSee($user->email)
            ->assertSee($user->name)
            ->assertSee($user->getImageUrl())
            ->assertSee($user->about);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_update_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(User::class)->raw();
        $user = User::create($attributes);

        // Act
        $attributes['name'] = $this->faker->name;
        $this->patch('/admin/users/' . $user->id, $attributes);

        // Assert
        $status->contains('admin')
            ? $this->assertDatabaseHas(app(User::class)->getTable(), \Arr::only($attributes, ['name']))
            : $this->assertDatabaseMissing(app(User::class)->getTable(), \Arr::only($attributes, ['name']));
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_destroy_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser();

        // Act
        $this->delete('/admin/users/' . $user->id);

        // Assert
        $status->contains('admin')
            ? $this->assertDatabaseMissing(app(User::class)->getTable(), ['name' => $user->name])
            : $this->assertDatabaseHas(app(User::class)->getTable(), ['name' => $user->name]);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_activate_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser(['is_active' => false]);

        // Act
        $this->patch('/admin/users/' . $user->id . '/set-active-status', ['active' => true]);

        // Assert
        $user->refresh();
        $status->contains('admin')
            ? $this->assertTrue($user->isActive())
            : $this->assertFalse($user->isActive());
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_deactivate_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser(['is_active' => true]);

        // Act
        $this->patch('/admin/users/' . $user->id . '/set-active-status', []);

        // Assert
        $user->refresh();
        $status->contains('admin')
            ? $this->assertFalse($user->isActive())
            : $this->assertTrue($user->isActive());
    }
}
