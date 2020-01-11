<?php

namespace Tests\Feature\RequestValidation;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreComment extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $this->actingAsUser();
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];

        // Act
        $response = $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Assert
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function the_empty_field_fails_the_validation_rules()
    {
        // Arrange
        $this->actingAsUser();
        $articles = factory(Article::class)->create();
        $attributes = ['body' => ''];

        // Act
        $response = $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Assert
        $response->assertSessionHasErrors('body');
    }
}
