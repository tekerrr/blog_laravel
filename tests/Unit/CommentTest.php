<?php

namespace Tests\Unit;

use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithRoles;

class CommentTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

    /** @test */
    public function the_class_is_using_can_be_activated_trait_correctly()
    {
        // Arrange
        $elements = factory(Comment::class, 2)->create(['is_active' => false]);

        // Act
        $elements->first()->activate();

        // Assert
        $this->assertTrue($elements->first()->isActive());
        $this->assertFalse($elements->last()->isActive());
    }

    /** @test */
    public function attribute_created_at_returns_russian_date()
    {
        // Arrange
        $comment = factory(Comment::class)->create([
            'created_at' => \Carbon\Carbon::create(2000, 1, 1, 0, 0, 0),
        ]);

        // Act
        $response = $comment->created_at;

        // Assert
        $this->assertEquals('1 января 2000 г. 00:00', $response);
    }

    /** @test */
    public function a_comment_has_a_user()
    {
        // Arrange
        $user = $this->createUser();
        $comment = factory(Comment::class)->create(['user_id' => $user->id]);

        // Act
        $owner = $comment->user;

        // Assert
        $this->assertEquals($owner->name, $user->name);
    }

    /** @test */
    public function a_comment_has_a_article()
    {
        // Arrange
        $article = factory(\App\Article::class)->create();
        $comment = factory(Comment::class)->create(['article_id' => $article->id]);

        // Act
        $commentArticle = $comment->article;

        // Assert
        $this->assertEquals($commentArticle->title, $article->title);
    }
}
