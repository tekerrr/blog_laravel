<?php

namespace Tests\Feature\RequestValidation;

use App\Page;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdatePage extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_title' => ['title', ''],
            'short_title' => ['title', $faker->regexify('[A-Za-z]{4}')],
            'long_title' => ['title', $faker->regexify('[A-Za-z]{110}')],
            'empty_body' => ['body', ''],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsRole('author');
        $attributes = factory(Page::class)->raw();
        $page = Page::create($attributes);

        // Act
        $attributes['title'] = $this->faker->words(4, true);
        $response = $this->patch('/admin/pages/' . $page->id, $attributes);

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
        $page = Page::create($attributes);

        // Act
        $attributes[$field] = $value;
        $response = $this->patch('/admin/pages/' . $page->id, $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }
}
