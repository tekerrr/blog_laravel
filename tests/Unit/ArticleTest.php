<?php

namespace Tests\Unit;

use App\Article;
use App\Events\ArticlePublished;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

    /** @test */
    public function article_activation_creates_the_article_publish_event()
    {
        // Arrange
        \Event::fake([ArticlePublished::class]);
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $article->activate();

        // Assert
        \Event::assertDispatched(ArticlePublished::class);
    }

    /** @test */
    public function article_deactivation_does_not_create_the_article_publish_event()
    {
        // Arrange
        \Event::fake([ArticlePublished::class]);
        $article = factory(Article::class)->create(['is_active' => true]);

        // Act
        $article->deactivate();

        // Assert
        \Event::assertNotDispatched(ArticlePublished::class);
    }

    /** @test */
    public function article_updating_does_not_create_the_article_publish_event()
    {
        // Arrange
        \Event::fake([ArticlePublished::class]);
        $article = factory(Article::class)->create(['is_active' => true]);

        // Act
        $article->update(['title' => $this->faker->words(4, true)]);

        // Assert
        \Event::assertNotDispatched(ArticlePublished::class);
    }
}
