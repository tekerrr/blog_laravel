<?php

namespace Tests\Feature\RequestValidation;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUser extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_name'         => ['name', ''],
            'not_a_string_name'  => ['name', ['array']],
            'long_name'          => ['name', $faker->regexify('[A-Za-z]{260}')],
            'empty_email'        => ['email', ''],
            'not_a_string_email' => ['email', ['array']],
            'invalid_email'      => ['email', 'test.ru'],
            'long_email'         => ['email', $faker->regexify('[A-Za-z]{260}')],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsRole('admin');
        $attributes = factory(User::class)->raw();
        $user = User::create($attributes);

        // Act
        $attributes['name'] = $this->faker->name;
        $response = $this->patch('/admin/users/' . $user->id, $attributes);;

        // Assert
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     * @param string $field
     * @param $value
     */
    public function the_invalid_data_fails_the_validation_rules(string $field, $value)
    {
        // Arrange
        $this->actingAsRole('admin');
        $attributes = factory(User::class)->raw();
        $user = User::create($attributes);

        // Act
        $attributes[$field] = $value;
        $response = $this->patch('/admin/users/' . $user->id, $attributes);;

        // Assert
        $response->assertSessionHasErrors([$field]);
    }

    /** @test */
    public function the_non_unique_name_fails_validation_rules()
    {
        // Arrange
        $this->actingAsRole('admin');
        $name = $this->faker->name;
        factory(User::class)->create(['name' => $name]);
        $attributes = factory(User::class)->raw();
        $user = User::create($attributes);

        // Act
        $attributes['name'] = $name;
        $response = $this->patch('/admin/users/' . $user->id, $attributes);

        // Assert
        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function the_non_unique_email_fails_validation_rules()
    {
        // Arrange
        $this->actingAsRole('admin');
        $email = $this->faker->email;
        factory(User::class)->create(['email' => $email]);
        $attributes = factory(User::class)->raw();
        $user = User::create($attributes);

        // Act
        $attributes['email'] = $email;
        $response = $this->patch('/admin/users/' . $user->id, $attributes);

        // Assert
        $response->assertSessionHasErrors(['email']);
    }
}
