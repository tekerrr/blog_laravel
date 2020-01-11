<?php

namespace Tests\Feature\RequestValidation;

use App\Subscriber;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreSubscriber extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
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
        $attributes = ['email' => $this->faker->email];

        // Act
        $response = $this->post('/subscriber', $attributes);

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
        $attributes[$field] = $value;

        // Act
        $response = $this->post('/subscriber', $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }

    /** @test */
    public function the_not_unique_email_passes_the_validation_rules()
    {
        // Arrange
        $attributes = ['email' => $this->faker->email];
        Subscriber::create($attributes);

        // Act
        $response = $this->post('/subscriber', $attributes);

        // Assert
        $response->assertSessionHasNoErrors();
    }
}
