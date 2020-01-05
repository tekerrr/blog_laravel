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
        $article = factory(Article::class)->create([
            'created_at' => \Carbon\Carbon::create(2000, 1, 1, 0, 0, 0),
        ]);

        // Act
        $response = $article->created_at;

        // Assert
        $this->assertEquals('1 января 2000 г. 00:00', $response);
    }

    /** @test */
    public function a_articles_can_have_comments()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $comments = factory(\App\Comment::class, 2)->make(['article_id' => '']);

        // Act
        $article->comments()->saveMany($comments);

        // Assert
        $this->assertEquals($article->comments->first()->body, $comments->first()->body);
        $this->assertEquals($article->comments->last()->body, $comments->last()->body);
    }

    /** @test */
    public function method_previous_returns_previous_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $previous = factory(Article::class)->create(['is_active' => false]);
        $article = factory(Article::class)->create();

        // Act
        $response = $article->previous();

        // Assert
        $this->assertEquals($previous->body, $response->body);
    }

    /** @test */
    public function method_next_returns_next_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $article = factory(Article::class)->create();
        $next = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $article->next();

        // Assert
        $this->assertEquals($next->body, $response->body);
    }
}
