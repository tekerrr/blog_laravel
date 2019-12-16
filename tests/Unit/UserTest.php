<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithRoles;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

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
    public function a_role_can_be_added_to_a_user()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $user->addRole($role = $this->faker->word);

        // Assert
        $this->assertEquals($role, $user->roles->first()->role);
    }

    /** @test */
    public function an_admin_is_defined_as_admin()
    {
        // Arrange
        $user = $this->createAdmin();

        // Act
        $response = $user->isAdmin();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function an_author_is_not_defined_as_admin()
    {
        // Arrange
        $user = $this->createAuthor();

        // Act
        $response = $user->isAdmin();

        // Assert
        $this->assertFalse($response);
    }

    /** @test */
    public function a_user_is_not_defined_as_admin()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $response = $user->isAdmin();

        // Assert
        $this->assertFalse($response);
    }

    /** @test */
    public function an_author_is_defined_as_author()
    {
        // Arrange
        $user = $this->createAuthor();

        // Act
        $response = $user->isAuthor();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function an_admin_is_not_defined_as_author()
    {
        // Arrange
        $user = $this->createAdmin();

        // Act
        $response = $user->isAuthor();

        // Assert
        $this->assertFalse($response);
    }

    /** @test */
    public function a_user_is_not_defined_as_author()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $response = $user->isAuthor();

        // Assert
        $this->assertFalse($response);
    }

    /** @test */
    public function an_admin_is_defined_as_stuff()
    {
        // Arrange
        $user = $this->createAdmin();

        // Act
        $response = $user->isStuff();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function an_author_is_defined_as_stuff()
    {
        // Arrange
        $user = $this->createAuthor();

        // Act
        $response = $user->isStuff();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function an_user_is_not_defined_as_stuff()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $response = $user->isStuff();

        // Assert
        $this->assertFalse($response);
    }

    /** @test */
    public function a_user_with_subscription_is_defined_as_subscriber()
    {
        // Arrange
        $user = $this->createUser();
        $user->subscription()->create();

        // Act
        $response = $user->isSubscriber();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function a_user_without_subscription_is_not_defined_as_subscriber()
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $response = $user->isSubscriber();

        // Assert
        $this->assertFalse($response);
    }
}
