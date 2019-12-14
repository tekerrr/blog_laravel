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

    /** @test */
    public function an_active_article_is_defined_as_active()
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => true]);

        // Act
        $response = $article->isActive();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function an_inactive_article_is_not_defined_as_active()
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $article->isActive();

        // Assert
        $this->assertFalse($response);
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
}
