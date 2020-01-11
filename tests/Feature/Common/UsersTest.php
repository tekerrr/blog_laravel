<?php

namespace Tests\Feature\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithImage;

class UsersTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    /** @test */
    public function active_user_can_login()
    {
        // Arrange
        $user = $this->createUser(['is_active' => true]);

        // Act
        $this->followingRedirects()->post('/login', ['email' => $user->email, 'password' => 'password']);

        // Assert
        $this->assertAuthenticated();
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        // Arrange
        $user = $this->createUser(['is_active' => false]);

        // Act
        $response = $this->followingRedirects()->post('/login', ['email' => $user->email, 'password' => 'password']);

        // Assert
        $this->assertGuest();
        $response->assertSeeText('Ваш пользователь заблокирован!');
    }

    /** @test */
    public function deactivated_user_will_be_logout()
    {
        // Arrange
        $user = $this->actingAsUser();

        // Act
        $user->deactivate();
        $response = $this->get('/articles');

        // Assert
        $this->assertGuest();
    }

    /**
     * @test
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_user_can_view_his_account_page($role, $auth)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/account');

        // Assert
        $auth
            ? $response
                ->assertViewIs('account.edit')
                ->assertSeeText('Личный кабинет')
            : $response
                ->assertRedirect('/login');
    }

    /**
     * @test
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_can_view_change_password_page($role, $auth)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/password');

        // Assert
        $auth
            ? $response
                ->assertViewIs('auth.password.edit')
                ->assertSeeText('Смена пароля')
            : $response
                ->assertRedirect('/login');
    }

    /**
     * @test
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_user_can_update_his_account_data($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        $attributes = optional($visitor)->getAttributes();

        // Act
        $attributes['name'] = $this->faker->name;
        $response = $this->patch('/account', $attributes);

        // Assert
        $auth
            ? $this->assertDatabaseHas('users', \Arr::only($attributes, ['name']))
            : $response->assertRedirect('/login');
    }

    /**
     * @test
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_user_can_update_his_password($role, $auth)
    {
        // Arrange
        $currentPassword = $this->faker->password;
        $newPassword = $this->faker->password;
        $visitor = $this->actingAsRole($role, ['password' => \Hash::make($currentPassword)]);
        $attributes = [
            'current-password' => $currentPassword,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        // Act
        $response = $this->patch('/password', $attributes);

        // Assert
        if ($auth) {
            $this->post('/logout');
            $this->post('/login', ['email' => $visitor->email, 'password' => $newPassword]);
            $this->assertTrue(auth()->check());
        } else {
            $response->assertRedirect('/login');
        }
    }

    /** @test */
    public function a_user_cannot_update_his_password_with_wrong_current_password()
    {
        // Arrange
        $this->actingAsUser();
        $attributes = [
            'current-password' => $this->faker->password,
            'password' => ($newPassword = $this->faker->password),
            'password_confirmation' => $newPassword,
        ];

        // Act
        $this->patch('/password', $attributes);

        // Assert
        $this->assertEquals('Неверный пароль.', session('message'));
    }
}
