<?php

namespace Tests\Feature\Common;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithRoles;

class CommentsTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

    /** @test */
    public function a_guest_cannot_add_a_comment_to_the_article()
    {
        // Arrange
        $article = factory(Article::class)->create();

        // Act
        $response = $this->post('/articles/'. $article->id . '/comments', []);

        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_add_a_comment_to_the_article()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsUser();

        // Act
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Assert
        $this->assertDatabaseHas((new \App\Comment())->getTable(), $attributes);
    }

    /** @test */
    public function a_users_comment_is_hidden_until_moderation()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsUser();
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Act
        $response = $this->get('/articles/'. $articles->id); ;

        // Assert
        $response->assertDontSeeText($attributes['body']);
    }

    /** @test */
    public function an_author_can_add_a_comment_to_the_article()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsAuthor();

        // Act
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Assert
        $this->assertDatabaseHas((new \App\Comment())->getTable(), $attributes);
    }

    /** @test */
    public function an_author_comment_is_shown_without_moderation()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsAuthor();
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Act
        $response = $this->get('/articles/'. $articles->id); ;

        // Assert
        $response->assertSeeText($attributes['body']);
    }

    /** @test */
    public function an_admin_can_add_a_comment_to_the_article()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsAdmin();

        // Act
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Assert
        $this->assertDatabaseHas((new \App\Comment())->getTable(), $attributes);
    }

    /** @test */
    public function an_admin_comment_is_shown_without_moderation()
    {
        // Arrange
        $articles = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];
        $this->actingAsAdmin();
        $this->post('/articles/'. $articles->id . '/comments', $attributes);

        // Act
        $response = $this->get('/articles/'. $articles->id); ;

        // Assert
        $response->assertSeeText($attributes['body']);
    }
}
