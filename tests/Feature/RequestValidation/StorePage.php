<?php

namespace Tests\Feature\RequestValidation;

use App\Page;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorePage extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_title' => ['title', ''],
            'short_title' => ['title', $faker->regexify('[A-Za-z]{4}')],
            'long_title'  => ['title', $faker->regexify('[A-Za-z]{110}')],
            'empty_body'  => ['body', ''],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsRole('author');
        $attributes = factory(Page::class)->raw();

        // Act
        $response = $this->post('/admin/pages', $attributes);

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
        $this->actingAsRole('author');
        $attributes = factory(Page::class)->raw();

        // Act
        $attributes[$field] = $value;
        $response = $this->post('/admin/pages', $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }
}
