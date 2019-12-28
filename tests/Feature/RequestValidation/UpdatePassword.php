<?php

namespace Tests\Feature\RequestValidation;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePassword extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_current_password'        => ['current-password', ''],
            'not_a_string_current_password' => ['current-password', ['array']],
            'short_current_password'        => ['current-password', $faker->regexify('[A-Za-z]{3}')],
            'empty_password'                => ['password', ''],
            'not_a_string_password'         => ['password', ['array']],
            'short_password'                => ['password', $faker->regexify('[A-Za-z]{3}')],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsUser();
        $attributes = [
            'current-password' => $this->faker->password,
            'password' => $this->faker->password,
        ];
        $attributes['password_confirmation'] = $attributes['password'];

        // Act
        $response = $this->patch('/password', $attributes);

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
        $this->actingAsUser();
        $attributes = [
            'current-password' => $this->faker->password,
            'password' => $this->faker->password,
        ];

        // Act
        $attributes[$field] = $value;
        $attributes['password_confirmation'] = $attributes['password'];
        $response = $this->patch('/password', $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }

    public function mismatching_passwords_fails_the_validation_rules(string $field, $value)
    {
        // Arrange
        $this->actingAsUser();
        $attributes = [
            'current-password' => $this->faker->password,
            'password' => $this->faker->password,
            'password_confirmation' => $this->faker->password,
        ];

        // Act
        $response = $this->patch('/password', $attributes);

        // Assert
        $response->assertSessionHasErrors('password');
    }
}
