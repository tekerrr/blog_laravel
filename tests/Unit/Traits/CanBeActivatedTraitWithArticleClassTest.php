<?php

namespace Tests\Unit\Traits;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanBeActivatedTraitWithArticleClassTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function method_active_returns_only_published_articles()
    {
        // Arrange
        $publishedArticlesNumber = 2;
        factory(Article::class, $publishedArticlesNumber)->create(['is_active' => true]);
        factory(Article::class)->create(['is_active' => false]);

        // Act
        $articles = Article::active();

        // Assert
        $this->assertEquals($articles->count(), $publishedArticlesNumber);
    }

    /**
     * @test
     * @dataProvider booleanProvider
     * @param boolean $boolean
     */
    public function only_active_article_is_defined_as_active($boolean)
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => $boolean]);

        // Act
        $response = $article->isActive();

        // Assert
        $this->assertEquals($boolean, $response);
    }

    /** @test */
    public function a_article_can_be_activated()
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $article->activate();

        // Assert
        $this->assertTrue($article->isActive());
    }

    /** @test */
    public function a_article_can_be_deactivated()
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => true]);

        // Act
        $article->deactivate();

        // Assert
        $this->assertFalse($article->isActive());
    }

    /** @test */
    public function method_previous_active_returns_previous_active_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $previous = factory(Article::class)->create();
        factory(Article::class)->create(['is_active' => false]);
        $article = factory(Article::class)->create();

        // Act
        $response = $article->previousActive();

        // Assert
        $this->assertEquals($previous->body, $response->body);
    }

    /** @test */
    public function method_next_active_returns_next_active_article()
    {
        // Arrange
        factory(Article::class, 2)->create();
        $article = factory(Article::class)->create();
        factory(Article::class)->create(['is_active' => false]);
        $next = factory(Article::class)->create();

        // Act
        $response = $article->nextActive();

        // Assert
        $this->assertEquals($next->body, $response->body);
    }
}
