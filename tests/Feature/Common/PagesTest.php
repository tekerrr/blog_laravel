<?php

namespace Tests\Feature\Common;

use App\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function anyone_can_view_the_page_page()
    {
        // Arrange
        $page = factory(Page::class)->create();

        // Act
        $response = $this->get('/pages/' . $page->id);

        // Assert
        $response
            ->assertViewIs('pages.show')
            ->assertSeeText($page->body);
    }

    /** @test */
    public function anyone_cannot_view_the_inactive_page_page()
    {
        // Arrange
        $page = factory(Page::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/pages/' . $page->id);

        // Assert
        $response->assertStatus(404);
    }

    /** @test */
    public function anyone_can_view_page_titles_on_the_navbar()
    {
        // Arrange
        $pages = factory(Page::class, 2)->create();

        // Act
        $response = $this->get('/articles/');

        // Assert
        $response
            ->assertSeeText($pages->first()->title)
            ->assertSeeText($pages->last()->title);
    }

    /** @test */
    public function anyone_can_view_only_active_page_titles_on_the_navbar()
    {
        // Arrange
        $activePage = factory(Page::class)->create();
        $inactivePage = factory(Page::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/articles/');

        // Assert
        $response
            ->assertSeeText($activePage->title)
            ->assertDontSeeText($inactivePage->title);
    }
}
