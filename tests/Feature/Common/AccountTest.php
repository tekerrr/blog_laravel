<?php

namespace Tests\Feature\Common;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\WithImage;
use Tests\WithRoles;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles, WithImage;

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_view_his_account_page($role, $auth)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/account');

        // Assert
        if ($auth) {
            $response->assertViewIs('account.edit');
            $response->assertSeeText('Личный кабинет');
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_view_change_password_page($role, $auth)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/password');

        // Assert
        if ($auth) {
            $response->assertViewIs('auth.password.edit');
            $response->assertSeeText('Смена пароля');
        } else {
            $response->assertRedirect('/login');
        }
    }

    /** @test */
    public function a_user_can_view_his_avatar_on_his_account_page()
    {
        // Arrange
        $user = $this->actingAsUser();
        $user->image()->create(['path' => $this->getImage()]);

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee(\Storage::url($this->image));
    }

    /** @test */
    public function a_user_without_avatar_can_view_default_avatar_on_his_account_page()
    {
        // Arrange
        $this->actingAsUser();

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee('/img/default-avatar.png');
    }

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_update_his_account_data($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        $attributes = optional($visitor)->getAttributes();

        // Act
        $attributes['name'] = $this->faker->name;
        $response = $this->patch('/account', $attributes);

        // Assert
        if ($auth) {
            $this->assertDatabaseHas('users', Arr::only($attributes, ['name']));
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_update_his_password($role, $auth)
    {
        // Arrange
        $currentPassword = $this->faker->password;
        $newPassword = $this->faker->password;
        $visitor = $this->actingAsRole($role, ['password' => Hash::make($currentPassword)]);
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

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_upload_new_avatar($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);

        // Act
        $response = $this->json('PATCH','/avatar', [
            'avatar' => $this->getUploadedImage(),
        ]);

        // Assert
        if ($auth) {
            \Storage::disk('local')->assertExists($this->image = $visitor->image->path);
        } else {
            $response->assertStatus(401);
        }
    }

    /**
     * @test
     * @dataProvider basicVisitorProvider
     * @param $role
     * @param $auth
     */
    public function a_user_can_delete_his_avatar($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        if ($auth) {
            $this->json('PATCH','/avatar', [
                'avatar' => $this->getUploadedImage(),
            ]);
        }

        // Act
        $response = $this->delete('/avatar');

        // Assert
        if ($auth) {
            \Storage::disk('local')->assertMissing($visitor->image->path);
        } else {
            $response->assertRedirect('/login');
        }
    }
}
