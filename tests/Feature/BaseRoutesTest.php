<?php

namespace Tests\Feature;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function main_redirect()
    {
        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(302);
    }

    /** @test */
    public function anyone_can_view_the_article_page()
    {
        // Arrange
        $article = factory(Article::class)->create();

        // Act
        $response = $this->get('/articles/' . $article->id);

        // Assert
        $response->assertViewIs('articles.show');
        $response->assertSeeText($article->body);
    }
}
