<?php

namespace Tests\Unit;

use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_role_can_have_users()
    {
        // Arrange
        $role = factory(Role::class)->create();
        $users = factory(\App\User::class, 2)->create();

        // Act
        $role->users()->attach($users);

        // Assert
        $this->assertEquals($users->first()->name, $role->users->first()->name);
        $this->assertEquals($users->last()->name, $role->users->last()->name);
    }
}
