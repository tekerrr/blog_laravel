<?php

namespace Tests\Feature\Common;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_view_his_account_page($role, $can)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/account');

        // Assert
        if ($can) {
            $response->assertViewIs('account.edit');
            $response->assertSeeText('Личный кабинет');
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_view_change_password_page($role, $can)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/password');

        // Assert
        if ($can) {
            $response->assertViewIs('auth.password.edit');
            $response->assertSeeText('Смена пароля');
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider AuthRoleProvider
     * @param $role
     */
    public function a_visitor_can_view_his_avatar_on_his_account_page($role)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        $path = $this->getImage();
        $visitor->image()->create(['path' => $path]);

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee(\Storage::url($path));

        // Clean
        $this->deleteImage($path);
    }

    /**
     * @test
     * @dataProvider AuthRoleProvider
     * @param $role
     */
    public function a_visitor_without_avatar_can_view_default_avatar_on_his_account_page($role)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee('/img/default-avatar.png');
    }

    /**
     * @test
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_update_his_account_data($role, $can)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        $attributes = optional($visitor)->getAttributes();

        // Act
        $attributes['name'] = $this->faker->name;
        $response = $this->patch('/account', $attributes);

        // Assert
        if ($can) {
            $this->assertDatabaseHas('users', Arr::only($attributes, ['name']));
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_update_his_password($role, $can)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        $currentPassword = $this->faker->password;
        $newPassword = $this->faker->password;
        optional($visitor)->update(['password' => Hash::make($currentPassword)]);
        $attributes = [
            'current-password' => $currentPassword,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        // Act
        $response = $this->patch('/password', $attributes);

        // Assert
        if ($can) {
            $this->post('/logout');
            $this->post('/login', ['email' => $visitor->email, 'password' => $newPassword]);
            $this->assertTrue(auth()->check());
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider AuthRoleProvider
     * @param $role
     */
    public function a_visitor_cannot_update_his_password_with_wrong_current_password($role)
    {
        // Arrange
        $this->actingAsRole($role);
        $newPassword = $this->faker->password;
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

    /**
     * @test
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_upload_new_avatar($role, $can)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);

        // Act
        $response = $this->json('PATCH','/avatar', [
            'avatar' => UploadedFile::fake()->image('new_avatar.jpg'),
        ]);

        // Assert
        if ($can) {
            \Storage::disk('local')->assertExists($visitor->image->path);
            $this->deleteImage($visitor->image->path);
        } else {
            $response->assertStatus(401);
        }
    }

    /**
     * @test
     * @dataProvider RoleProvider
     * @param $role
     * @param $can
     */
    public function a_visitor_can_delete_his_avatar($role, $can)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        if ($can) {
            $this->json('PATCH','/avatar', [
                'avatar' => UploadedFile::fake()->image('new_avatar.jpg'),
            ]);
        }

        // Act
        $response = $this->delete('/avatar');

        // Assert
        if ($can) {
            \Storage::disk('local')->assertMissing($visitor->image->path);
        } else {
            $response->assertRedirect('/login');
        }
    }
}
