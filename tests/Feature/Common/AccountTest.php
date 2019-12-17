<?php

namespace Tests\Feature\Common;

use App\User;
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

    /** @test */
    public function a_user_can_view_his_avatar_on_his_account_page()
    {
        // Arrange
        $user = $this->actingAsUser();
        $path = $this->getImage();
        $user->image()->create(['path' => $path]);

        // Act
        $response = $this->get('/account');

        // Assert
        $response->assertSee(\Storage::url($path));

        // Clean
        $this->deleteImage($path);
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

    /** @test */
    public function a_user_can_upload_new_avatar()
    {
        // Arrange
        $user = $this->actingAsUser();

        // Act
        $this->json('PATCH','/avatar', [
            'avatar' => UploadedFile::fake()->image('new_avatar.jpg'),
        ]);

        // Assert
        \Storage::disk('local')->assertExists($user->image->path);

        // Clean
        $this->deleteImage($user->image->path);
    }

    public function a_guest_cannot_upload_new_avatar()
    {
        // Act
        $response = $this->patch('/avatar', []);

        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_delete_his_avatar()
    {
        // Arrange
        $user = $this->actingAsUser();
        $this->json('PATCH','/avatar', [
            'avatar' => UploadedFile::fake()->image('new_avatar.jpg'),
        ]);

        // Act
        $this->delete('/avatar');

        // Assert
        \Storage::disk('local')->assertMissing($user->image->path);
    }

    public function a_guest_cannot_delete_avatar()
    {
        // Act
        $response = $this->delete('/avatar', []);

        // Assert
        $response->assertRedirect('/login');
    }
}
