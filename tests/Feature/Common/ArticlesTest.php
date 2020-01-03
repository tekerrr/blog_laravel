<?php

namespace Tests\Feature\Common;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function anyone_can_view_the_article_list_page()
    {
        // Act
        $response = $this->get('/articles');

        // Assert
        $response
            ->assertViewIs('articles.index')
            ->assertSeeText('Главная');
    }

    /** @test */
    public function the_article_list_page_shows_only_active_articles()
    {
        // Arrange
        $activeArticle = factory(Article::class)->create(['is_active' => true]);
        $inactiveArticle = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/articles');

        // Assert
        $response
            ->assertSeeText($activeArticle->title)
            ->assertDontSeeText($inactiveArticle->title);
    }

    /** @test */
    public function anyone_can_view_the_article_page()
    {
        // Arrange
        $article = factory(Article::class)->create();

        // Act
        $response = $this->get('/articles/' . $article->id);

        // Assert
        $response
            ->assertViewIs('articles.show')
            ->assertSeeText($article->body);
    }

    /** @test */
    public function anyone_can_view_the_article_page_with_comments()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $articleComments = factory(\App\Comment::class, 2)->create(['article_id' => $article->id]);
        $otherComment = factory(\App\Comment::class)->create();

        // Act
        $response = $this->get('/articles/' . $article->id);

        // Assert
        $response
            ->assertSeeText($articleComments->first()->body)
            ->assertSeeText($articleComments->last()->body)
            ->assertDontSeeText($otherComment->body);
    }

    /** @test */
    public function anyone_cannot_view_the_inactive_article_page()
    {
        // Arrange
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/articles/' . $article->id);

        // Assert
        $response->assertStatus(404);
    }
}
