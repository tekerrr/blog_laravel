<?php

namespace Tests\Feature\Admin;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_article_list_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/articles');

        // Assert
        if ($status->contains('stuff')) {
            $response
                ->assertViewIs('admin.articles.index')
                ->assertSeeText('Статьи');
        } elseif ($status->contains('auth')) {
            $response->assertStatus(403);
        } else {
            $response->assertRedirect('/login');
        }
    }

    /** @test */
    public function the_article_list_page_shows_all_articles()
    {
        // Arrange
        $this->actingAsRole('author');
        $activeArticle = factory(Article::class)->create(['is_active' => true]);
        $inactiveArticle = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/admin/articles');

        // Assert
        $response
            ->assertSeeText($activeArticle->title)
            ->assertSeeText($inactiveArticle->title);
    }

//    /** @test */
//    public function anyone_can_view_the_article_page()
//    {
//        // Arrange
//        $article = factory(Article::class)->create();
//
//        // Act
//        $response = $this->get('/articles/' . $article->id);
//
//        // Assert
//        $response->assertViewIs('articles.show');
//        $response->assertSeeText($article->body);
//    }
//
//    /** @test */
//    public function anyone_can_view_the_article_page_with_comments()
//    {
//        // Arrange
//        $article = factory(Article::class)->create();
//        $articleComments = factory(\App\Comment::class, 2)->create(['article_id' => $article->id]);
//        $otherComment = factory(\App\Comment::class)->create();
//
//        // Act
//        $response = $this->get('/articles/' . $article->id);
//
//        // Assert
//        $response->assertSeeText($articleComments->first()->body);
//        $response->assertSeeText($articleComments->last()->body);
//        $response->assertDontSeeText($otherComment->body);
//    }
//
//    /** @test */
//    public function anyone_cannot_view_the_inactive_article_page()
//    {
//        // Arrange
//        $article = factory(Article::class)->create(['is_active' => false]);
//
//        // Act
//        $response = $this->get('/articles/' . $article->id);
//
//        // Assert
//        $response->assertStatus(404);
//    }
}
