<?php

namespace Tests\Feature\RequestValidation;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateSettings extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_paginator_items'                       => ['paginator_items', ''],
            'not_a_number_paginator_items'                => ['paginator_items', $faker->word],
            'too_small_paginator_items'                   => ['paginator_items', 0],
            'empty_navbar_articles'                       => ['navbar_articles', ''],
            'not_a_number_navbar_articles'                => ['navbar_articles', $faker->word],
            'too_small_navbar_articles'                   => ['navbar_articles', 0],
            'empty_custom_paginator_items'                => ['custom_paginator_items', ''],
            'not_selected_element_custom_paginator_items' => ['custom_paginator_items', $faker->word],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsRole('admin');
        $attributes = [
            'paginator_items'        => $this->faker->numberBetween(1, 5),
            'navbar_articles'        => $this->faker->numberBetween(1, 5),
            'custom_paginator_items' => $this->faker->randomElement(['10', '20', '50', '200', 'all']),
        ];

        // Act
        $response = $this->patch('/admin/settings', $attributes);

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
        $attributes = [
            'paginator_items'        => $this->faker->numberBetween(1, 5),
            'navbar_articles'        => $this->faker->numberBetween(1, 5),
            'custom_paginator_items' => $this->faker->randomElement(['10', '20', '50', '200', 'all']),
        ];

        // Act
        $attributes[$field] = $value;
        $response = $this->patch('/admin/settings', $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }
}
