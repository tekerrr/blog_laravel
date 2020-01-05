<?php

namespace Tests\Feature\RequestValidation;

use App\Article;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\WithImage;

class UpdateArticle extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_title' => ['title', ''],
            'short_title' => ['title', $faker->regexify('[A-Za-z]{4}')],
            'long_title' => ['title', $faker->regexify('[A-Za-z]{110}')],
            'empty_abstract' => ['abstract', ''],
            'long_abstract' => ['abstract', $faker->regexify('[A-Za-z]{260}')],
            'empty_body' => ['body', ''],
            'not_a_file_image'  => ['image', ['array']],
            'not_a_image_image' => ['image', UploadedFile::fake()->create('new_file.txt')],
            'big_size_image'    => ['image', UploadedFile::fake()->create('new_image.jpg', 5300)],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsRole('author');
        $attributes = factory(Article::class)->raw();
        $attributes['image'] = $this->getUploadedImage();
        $article = Article::create($attributes);

        // Act
        $attributes['title'] = $this->faker->words(3, true);
        $response = $this->patch('/admin/articles/' . $article->id, $attributes);

        // Assert
        $response->assertSessionHasNoErrors();

        // Clean
        $this->image = Article::first()->image->path;
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
        $attributes = factory(Article::class)->raw();
        $attributes['image'] = $this->getUploadedImage();
        $article = Article::create($attributes);

        // Act
        $attributes[$field] = $value;
        $response = $this->patch('/admin/articles/' . $article->id, $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }
}
