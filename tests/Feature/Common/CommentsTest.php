<?php

namespace Tests\Feature\Common;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_authorized_visitor_can_add_a_comment_to_the_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];

        // Act
        $response = $this->post('/articles/'. $article->id . '/comments', $attributes);

        // Assert
        if ($status->contains('auth')) {
            $this->assertDatabaseHas((new \App\Comment())->getTable(), $attributes);
        } else {
            $response->assertRedirect('/login');
        }
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_comment_is_shown_without_moderation($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create();
        $attributes = ['body' => $this->faker->sentence];

        // Act
        $this->post('/articles/'. $article->id . '/comments', $attributes);
        $response = $this->get('/articles/'. $article->id);

        // Assert
        if ($status->contains('stuff')) {
            $response->assertSeeText($attributes['body']);
        } else {
            $response->assertDontSeeText($attributes['body']);
        }
    }
}
