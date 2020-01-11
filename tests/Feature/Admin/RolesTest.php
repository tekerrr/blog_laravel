<?php

namespace Tests\Feature\Admin;

use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class RolesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithImage;

    /** @test */
    public function user_editing_page_shows_all_roles()
    {
        // Arrange
        $this->actingAsRole('admin');
        $user = $this->createUser();
        $user->addRole($userRole = $this->faker->word);
        $otherRole = factory(Role::class)->create()->role;

        // Act
        $response = $this->get('/admin/users/' . $user->id . '/edit');

        // Assert
        $response
            ->assertSeeText($userRole)
            ->assertSeeText($otherRole);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_add_a_role_to_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser();
        $role = factory(Role::class)->create();

        // Act
        $this->patch('/admin/users/' . $user->id . '/roles/' . $role->id . '/add');

        // Assert
        $status->contains('admin')
            ? $this->assertTrue($user->hasRole($role->role))
            : $this->assertFalse($user->hasRole($role->role));
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_remove_a_role_from_the_user($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $user = $this->createUser();
        $role = factory(Role::class)->create();
        $user->addRole($role);

        // Act
        $this->patch('/admin/users/' . $user->id . '/roles/' . $role->id . '/remove');

        // Assert
        $status->contains('admin')
            ? $this->assertFalse($user->hasRole($role->role))
            : $this->assertTrue($user->hasRole($role->role));
    }
}
