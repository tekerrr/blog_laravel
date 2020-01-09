<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class AvatarsTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_upload_new_avatar_for_the_other_user($role, $status)
    {
        // Arrange
        $user = $this->actingAsUser();
        $this->patch('/avatar', ['avatar' => $this->getUploadedImage()]);
        $this->image = $user->image->path;
        auth()->logout();

        $this->actingAsRole($role);

        // Act
        $response = $this->patch('admin/users/' . $user->id . '/avatar', ['avatar' => $this->getUploadedImage()]);

        // Assert
        $user->refresh();
        $status->contains('admin')
            ? \Storage::disk('local')
                ->assertMissing($this->image)
                ->assertExists($this->image = $user->image->path)
            : \Storage::disk('local')
                ->assertExists($this->image);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_delete_the_other_user_avatar($role, $status)
    {
        // Arrange
        $user = $this->actingAsUser();
        $this->patch('/avatar', ['avatar' => $this->getUploadedImage()]);
        $this->image = $user->image->path;
        auth()->logout();

        $this->actingAsRole($role);

        // Act
        $this->delete('admin/users/' . $user->id . '/avatar');

        // Assert
        $status->contains('admin')
            ? \Storage::disk('local')->assertMissing($this->image)
            : \Storage::disk('local')->assertExists($this->image);
    }
}
