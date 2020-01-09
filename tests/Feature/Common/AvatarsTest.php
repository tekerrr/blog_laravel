<?php

namespace Tests\Feature\Common;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\WithImage;

class AvatarsTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

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
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_user_can_upload_new_avatar($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);

        // Act
        $response = $this->patch('/avatar', ['avatar' => $this->getUploadedImage()]);

        // Assert
        $auth
            ? \Storage::disk('local')->assertExists($this->image = $visitor->image->path)
            : $response->assertRedirect('/login');
    }

    /**
     * @test
     * @dataProvider baseVisitorProvider
     * @param $role
     * @param $auth
     */
    public function only_authorized_user_can_delete_his_avatar($role, $auth)
    {
        // Arrange
        $visitor = $this->actingAsRole($role);
        if ($auth) {
            $this->patch('/avatar', ['avatar' => $this->getUploadedImage()]);
        }

        // Act
        $response = $this->delete('/avatar');

        // Assert
        $auth
            ? \Storage::disk('local')->assertMissing($visitor->image->path)
            : $response->assertRedirect('/login');
    }
}
