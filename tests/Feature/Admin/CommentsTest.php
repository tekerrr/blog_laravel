<?php

namespace Tests\Feature\Admin;

use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class CommentsTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_comment_list_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/comments');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.comments.index')
                ->assertSeeText('Комментарии')
            : $response
                ->assertDontSeeText('Комментарии');
    }

    /** @test */
    public function the_article_list_page_shows_active_and_inactive_comments()
    {
        // Arrange
        $this->actingAsRole('author');
        $activeComment = factory(Comment::class)->create(['is_active' => true]);
        $inactiveComment = factory(Comment::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/admin/comments');

        // Assert
        $response
            ->assertSeeText($activeComment->body)
            ->assertSeeText($inactiveComment->body);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_destroy_the_comment($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $comment = factory(Comment::class)->create();

        // Act
        $this->delete('/admin/comments/' . $comment->id);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseMissing(app(Comment::class)->getTable(), ['body' => $comment->body])
            : $this->assertDatabaseHas(app(Comment::class)->getTable(), ['body' => $comment->body]);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_activate_the_comment($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $comment = factory(Comment::class)->create(['is_active' => false]);

        // Act
        $this->patch('/admin/comments/' . $comment->id . '/set-active-status', ['active' => true]);

        // Assert
        $comment->refresh();
        $status->contains('stuff')
            ? $this->assertTrue($comment->isActive())
            : $this->assertFalse($comment->isActive());
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_deactivate_the_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $comment = factory(Comment::class)->create(['is_active' => true]);

        // Act
        $this->patch('/admin/comments/' . $comment->id . '/set-active-status', []);

        // Assert
        $comment->refresh();
        $status->contains('stuff')
            ? $this->assertFalse($comment->isActive())
            : $this->assertTrue($comment->isActive());
    }
}
