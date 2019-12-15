<?php

namespace Tests\Feature\Common;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\WithRoles;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

    /** @test */
    public function a_user_can_view_his_account_page()
    {
        // Arrange
        $this->actingAsUser();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertViewIs('account.edit');
        $response->assertSeeText('Личный кабинет');
    }

    /** @test */
    public function an_author_can_view_his_account_page()
    {
        // Arrange
        $this->actingAsAuthor();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertViewIs('account.edit');
        $response->assertSeeText('Личный кабинет');
    }

    /** @test */
    public function an_admin_can_view_his_account_page()
    {
        // Arrange
        $this->actingAsAdmin();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertViewIs('account.edit');
        $response->assertSeeText('Личный кабинет');
    }

    /** @test */
    public function a_guest_cannot_view_the_account_page()
    {
        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_update_his_account_data()
    {
        // Arrange
        $attributes = factory(User::class)->raw();
        $this->actingAs(User::create($attributes));

        // Act
        $attributes['name'] = $this->faker->name;
        $this->patch('/account', $attributes);

        // Assert
        $this->assertDatabaseHas('users', Arr::only($attributes, ['name']));
    }

    /** @test */
    public function a_guest_cannot_update_account_data()
    {
        // Act
        $response = $this->patch('/account', []);

        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_update_his_password()
    {
        // Arrange
        $currentPassword = $this->faker->password;
        $newPassword = $this->faker->password;
        $user = factory(User::class)->create(['password' => Hash::make($currentPassword)]);
        $this->actingAs($user);
        $attributes = [
            'current-password' => $currentPassword,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        // Act
        $this->patch('/password', $attributes);
        $this->post('/logout');
        $response = $this->post('/login', ['email' => $user->email, 'password' => $newPassword]);

        // Assert
        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function a_user_cannot_update_his_password_with_wrong_current_password()
    {
        // Arrange
        $newPassword = $this->faker->password;
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $attributes = [
            'current-password' => $this->faker->password,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        // Act
        $this->patch('/password', $attributes);

        // Assert
        $this->assertEquals('Неверный пароль.', session('message'));
    }
}
