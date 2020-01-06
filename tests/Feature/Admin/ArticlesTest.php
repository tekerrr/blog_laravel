<?php

namespace Tests\Feature\Admin;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class ArticlesTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

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
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.articles.index')
                ->assertSeeText('Статьи')
            : $response
                ->assertDontSeeText('Статьи');
    }

    /** @test */
    public function the_article_list_page_shows_active_and_inactive_articles()
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

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_article_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create();

        // Act
        $response = $this->get('/admin/articles/' . $article->id);

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.articles.show')
                ->assertSeeText($article->body)
            : $response
                ->assertDontSeeText($article->body);
    }

    /** @test */
    public function stuff_can_view_the_inactive_article_page()
    {
        // Arrange
        $this->actingAsRole('author');
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/admin/articles/' . $article->id);

        // Assert
        $response
            ->assertViewIs('admin.articles.show')
            ->assertSeeText($article->body);
    }

    /** @test */
    public function stuff_can_view_the_article_page_with_all_comments()
    {
        // Arrange
        $this->actingAsRole('author');
        $article = factory(Article::class)->create();
        $activeArticleComment = factory(\App\Comment::class)->create(['article_id' => $article->id, 'is_active' => true]);
        $inactiveArticleComment = factory(\App\Comment::class)->create(['article_id' => $article->id, 'is_active' => false]);
        $otherComment = factory(\App\Comment::class)->create();

        // Act
        $response = $this->get('/admin/articles/' . $article->id);

        // Assert
        $response
            ->assertSeeText($activeArticleComment->body)
            ->assertSeeText($inactiveArticleComment->body)
            ->assertDontSeeText($otherComment->body);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_article_creation_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/articles/create');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.articles.create')
                ->assertSeeText('Добавить статью')
            : $response
                ->assertDontSeeText('Добавить статью');
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_create_an_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(Article::class)->raw();
        $attributes['image'] = $this->getUploadedImage();

        // Act
        $this->post('/admin/articles', $attributes);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseHas(app(Article::class)->getTable(), ['body' => $attributes['body']])
            : $this->assertDatabaseMissing(app(Article::class)->getTable(), ['body' => $attributes['body']]);

        // Clean
        $this->image = optional(\App\Image::first())->path;
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_article_editing_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create();

        // Act
        $response = $this->get('/admin/articles/' . $article->id . '/edit');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.articles.edit')
                ->assertSeeText('Редактировать статью')
            : $response
                ->assertDontSeeText('Редактировать статью');
    }

    /** @test */
    public function the_article_editing_page_shows_article_attributes()
    {
        // Arrange
        $this->actingAsRole('author');
        $article = factory(Article::class)->create();

        // Act
        $response = $this->get('/admin/articles/' . $article->id . '/edit');

        // Assert
        $response
            ->assertSee($article->title)
            ->assertSee($article->abstract)
            ->assertSee($article->getImageUrl())
            ->assertSee($article->body);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_update_the_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(Article::class)->raw();
        $article = Article::create($attributes);

        // Act
        $attributes['title'] = $this->faker->words(4, true);
        $this->patch('/admin/articles/' . $article->id, $attributes);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseHas(app(Article::class)->getTable(), $attributes)
            : $this->assertDatabaseMissing(app(Article::class)->getTable(), $attributes);
    }

    /** @test */
    public function stuff_can_update_articles_image()
    {
        // Arrange
        $this->actingAsRole('author');
        $attributes = factory(Article::class)->raw();
        $article = Article::create($attributes);

        // Act
        $attributes['image'] = $this->getUploadedImage();
        $this->patch('/admin/articles/' . $article->id, $attributes);

        // Assert
        \Storage::disk('local')->assertExists($this->image = $article->image->path);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_destroy_the_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create();

        // Act
        $this->delete('/admin/articles/' . $article->id);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseMissing(app(Article::class)->getTable(), ['body' => $article->body])
            : $this->assertDatabaseHas(app(Article::class)->getTable(), ['body' => $article->body]);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_activate_the_article($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $this->patch('/admin/articles/' . $article->id . '/set-active-status', ['active' => true]);

        // Assert
        $article->refresh();
        $status->contains('stuff')
            ? $this->assertTrue($article->isActive())
            : $this->assertFalse($article->isActive());
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
        $article = factory(Article::class)->create(['is_active' => true]);

        // Act
        $this->patch('/admin/articles/' . $article->id . '/set-active-status', []);

        // Assert
        $article->refresh();
        $status->contains('stuff')
            ? $this->assertFalse($article->isActive())
            : $this->assertTrue($article->isActive());
    }
}
