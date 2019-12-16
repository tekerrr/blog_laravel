<?php

namespace Tests\Unit\Traits;

use App\Article;
use App\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasImageTraitWithArticleClassTest extends TestCase
{
    use RefreshDatabase;

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
