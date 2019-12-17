<?php

namespace Tests\Unit\Traits;

use App\Article;
use App\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithImage;

class HasImageTraitWithArticleClassTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    /** @test */
    public function a_article_can_have_an_image()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $image = factory(Image::class)->state('withoutImageable')->make();

        // Act
        $article->image()->save($image);

        // Assert
        $this->assertEquals($image->path, $article->image->path);
    }

    /** @test */
    public function a_article_can_save_an_image()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $path = $this->faker->word;

        // Act
        $article->saveImage($path);

        // Assert
        $this->assertEquals($path, $article->image->path);
    }

    /** @test */
    public function a_article_can_delete_his_image()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $article->saveImage($this->faker->word);
        factory(Image::class, 2)->create();

        // Act
        $article->deleteImage();

        // Assert
        $this->assertEquals(2, Image::count());
    }

    /** @test */
    public function a_article_delete_old_image_while_saving_new_image()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $article->saveImage($oldPath = $this->faker->word);

        // Act
        $article->saveImage($newPath = $this->faker->word);

        // Assert
        $this->assertEquals(1, Image::count());
        $this->assertEquals($newPath, $article->image->path);
        $this->assertNotEquals($oldPath, $article->image->path);
    }

    /** @test */
    public function method_get_image_url_returns_image_url()
    {
        // Arrange
        $article = factory(Article::class)->create();
        $path = $this->getImage();
        $article->image()->create(['path' => $path]);

        // Act
        $response = $article->getImageUrl();

        // Assert
        $this->assertEquals(\Storage::url($path), $response);

        // Clean
        $this->deleteImage($path);
    }

    /** @test */
    public function an_image_is_deleted_when_the_article_is_deleted()
    {
        // Arrange
        factory(Image::class, 2)->create();
        $article = factory(Article::class)->create();
        $image = factory(Image::class)->state('withoutImageable')->make();
        $article->image()->save($image);

        // Act
        $article->delete();

        // Assert
        $this->assertEquals(2, \App\Image::count());
    }
}
