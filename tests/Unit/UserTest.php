<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function the_class_is_using_has_image_trait_correctly()
    {
        // Arrange
        $user = $this->createUser();
        $image = factory(\App\Image::class)->state('withoutImageable')->make();

        // Act
        $user->image()->save($image);

        // Assert
        $this->assertEquals($image->path, $user->image->path);
    }

    /** @test */
    public function the_class_is_using_can_be_activated_trait_correctly()
    {
        // Arrange
        $elements = factory(User::class, 2)->create(['is_active' => false]);

        // Act
        $elements->first()->activate();

        // Assert
        $this->assertTrue($elements->first()->isActive());
        $this->assertFalse($elements->last()->isActive());
    }

    /** @test */
    public function a_user_can_have_roles()
    {
        // Arrange
        $user = $this->createUser();
        $roles = factory(\App\Role::class, 2)->create();

        // Act
        $user->roles()->attach($roles);

        // Assert
        $this->assertTrue($user->hasRole($roles->first()->role));
        $this->assertTrue($user->hasRole($roles->last()->role));
        $this->assertFalse($user->hasRole($this->faker->words(2, true)));
        $this->assertFalse($user->hasRoles($this->faker->unique()->words(2)));
    }

    /** @test */
    public function a_user_can_have_subscription()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $user->subscription()->create();

        // Assert
        $this->assertEquals($user->email, $user->subscription->email);
    }

    /** @test */
    public function a_subscription_is_updated_when_the_user_is_updated()
    {
        // Arrange
        $user = $this->createUser();
        $user->subscription()->create();
        $oldEmail = $user->email;
        $newEmail = $this->faker->email;

        // Act
        $user->update(['email' => $newEmail]);

        // Assert
        $this->assertDatabaseMissing((new \App\Subscriber())->getTable(), ['email' => $oldEmail]);
        $this->assertDatabaseHas((new \App\Subscriber())->getTable(), ['email' => $newEmail]);
    }

    /** @test */
    public function a_subscription_is_deleted_when_the_user_is_deleted()
    {
        // Arrange
        $user = $this->createUser();
        $user->subscription()->create();

        // Act
        $user->delete();

        // Assert
        $this->assertDatabaseMissing((new \App\Subscriber())->getTable(), ['email' => $user->email]);
    }

    /** @test */
    public function a_role_can_be_added_to_the_user()
    {
        // Arrange
        $user = $this->createUser();
        $role = factory(\App\Role::class)->create();

        // Act
        $user->addRole($role);

        // Assert
        $this->assertEquals($role->role, $user->roles->first()->role);
    }

    /** @test */
    public function a_role_as_string_can_be_added_to_the_user()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $user->addRole($role = $this->faker->word);

        // Assert
        $this->assertEquals($role, $user->roles->first()->role);
    }

    /** @test */
    public function a_role_can_be_removed_from_the_user()
    {
        // Arrange
        $user = $this->createUser()->addRole($this->faker->word);

        // Act
        $user->removeRole($user->roles->first());

        // Assert
        $user->refresh();
        $this->assertNull($user->roles->first());
    }

    /** @test */
    public function a_role_as_string_can_be_removed_from_the_user()
    {
        // Arrange
        $user = $this->createUser()->addRole($role = $this->faker->word);

        // Act
        $user->removeRole($role);

        // Assert
        $user->refresh();
        $this->assertNull($user->roles->first());
    }

    /**
     * @test
     * @dataProvider userProvider
     * @param string $role
     * @param Collection $status
     */
    public function method_is_admin_validates_admin_role_correctly($role, $status)
    {
        // Arrange
        $user = $this->createUserWithRole($role);

        // Act
        $response = $user->isAdmin();

        // Assert
        $this->assertEquals($status->contains('admin'), $response);
    }

    /**
     * @test
     * @dataProvider userProvider
     * @param string $role
     * @param Collection $status
     */
    public function method_is_author_validates_author_role_correctly($role, $status)
    {
        // Arrange
        $user = $this->createUserWithRole($role);

        // Act
        $response = $user->isAuthor();

        // Assert
        $this->assertEquals($status->contains('author'), $response);
    }

    /**
     * @test
     * @dataProvider userProvider
     * @param string $role
     * @param Collection $status
     */
    public function method_is_stuff_validates_stuff_roles_correctly($role, $status)
    {
        // Arrange
        $user = $this->createUserWithRole($role);

        // Act
        $response = $user->isStuff();

        // Assert
        $this->assertEquals($status->contains('stuff'), $response);
    }

    /**
     * @test
     * @dataProvider booleanProvider
     * @param boolean $boolean
     */
    public function only_user_with_subscription_is_defined_as_subscriber($boolean)
    {
        // Arrange
        $user = $this->createUser();
        if ($boolean) {
            $user->subscription()->create();
        }

        // Act
        $response = $user->isSubscriber();

        // Assert
        $this->assertEquals($boolean, $response);
    }
}
