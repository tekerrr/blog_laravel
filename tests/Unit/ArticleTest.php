<?php

namespace Tests\Unit;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function attribute_created_at_returns_russian_date()
    {
        // Arrange
        $artice = factory(Article::class)->create([
            'created_at' => \Carbon\Carbon::create(2000, 1, 1, 0, 0, 0),
        ]);

        // Act
        $response = $artice->created_at;

        // Assert
        $this->assertEquals('1 января 2000 г. 00:00', $response);
    }

    /** @test */
    public function method_previous_returns_previous_active_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $previous = factory(Article::class)->create();
        factory(Article::class)->create(['is_active' => false]);
        $article = factory(Article::class)->create();

        // Act
        $response = $article->previous();

        // Assert
        $this->assertEquals($previous->body, $response->body);
    }

    /** @test */
    public function method_next_returns_next_active_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $article = factory(Article::class)->create();
        factory(Article::class)->create(['is_active' => false]);
        $next = factory(Article::class)->create();

        // Act
        $response = $article->next();

        // Assert
        $this->assertEquals($next->body, $response->body);
    }
}
