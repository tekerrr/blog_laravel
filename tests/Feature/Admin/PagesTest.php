<?php

namespace Tests\Feature\Admin;

use App\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\WithImage;

class PagesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithImage;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_page_list_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/pages');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.pages.index')
                ->assertSeeText('Статичные страницы')
            : $response
                ->assertDontSeeText('Статичные страницы');
    }

    /** @test */
    public function the_page_list_page_shows_active_and_inactive_articles()
    {
        // Arrange
        $this->actingAsRole('author');
        $activePage = factory(Page::class)->create(['is_active' => true]);
        $inactivePage = factory(Page::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/admin/pages');

        // Assert
        $response
            ->assertSeeText($activePage->title)
            ->assertSeeText($inactivePage->title);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_page_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $page = factory(Page::class)->create();

        // Act
        $response = $this->get('/admin/pages/' . $page->id);

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.pages.show')
                ->assertSeeText($page->body)
            : $response
                ->assertDontSeeText($page->body);
    }

    /** @test */
    public function stuff_can_view_the_inactive_page_page()
    {
        // Arrange
        $this->actingAsRole('author');
        $page = factory(Page::class)->create(['is_active' => false]);

        // Act
        $response = $this->get('/admin/pages/' . $page->id);

        // Assert
        $response
            ->assertViewIs('admin.pages.show')
            ->assertSeeText($page->body);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_page_creation_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/pages/create');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.pages.create')
                ->assertSeeText('Добавить статичную страницу')
            : $response
                ->assertDontSeeText('Добавить статичную страницу');
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_create_a_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(Page::class)->raw();

        // Act
        $this->post('/admin/pages', $attributes);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseHas(app(Page::class)->getTable(), ['body' => $attributes['body']])
            : $this->assertDatabaseMissing(app(Page::class)->getTable(), ['body' => $attributes['body']]);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_view_the_page_editing_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $page = factory(Page::class)->create();

        // Act
        $response = $this->get('/admin/pages/' . $page->id . '/edit');

        // Assert
        $status->contains('stuff')
            ? $response
                ->assertViewIs('admin.pages.edit')
                ->assertSeeText('Редактировать статичную страницу')
            : $response
                ->assertDontSeeText('Редактировать статичную страницу');
    }

    /** @test */
    public function the_page_editing_page_shows_page_attributes()
    {
        // Arrange
        $this->actingAsRole('author');
        $page = factory(Page::class)->create();

        // Act
        $response = $this->get('/admin/pages/' . $page->id . '/edit');

        // Assert
        $response
            ->assertSee($page->title)
            ->assertSee($page->body);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_update_the_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(Page::class)->raw();
        $page = Page::create($attributes);

        // Act
        $attributes['title'] = $this->faker->words(4, true);
        $this->patch('/admin/pages/' . $page->id, $attributes);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseHas(app(Page::class)->getTable(), $attributes)
            : $this->assertDatabaseMissing(app(Page::class)->getTable(), $attributes);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_destroy_the_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $page = factory(Page::class)->create();

        // Act
        $this->delete('/admin/pages/' . $page->id);

        // Assert
        $status->contains('stuff')
            ? $this->assertDatabaseMissing(app(Page::class)->getTable(), ['body' => $page->body])
            : $this->assertDatabaseHas(app(Page::class)->getTable(), ['body' => $page->body]);
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_activate_the_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $page = factory(Page::class)->create(['is_active' => false]);

        // Act
        $this->patch('/admin/pages/' . $page->id . '/set-active-status', ['active' => true]);

        // Assert
        $page->refresh();
        $status->contains('stuff')
            ? $this->assertTrue($page->isActive())
            : $this->assertFalse($page->isActive());
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_stuff_can_deactivate_the_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $page = factory(Page::class)->create(['is_active' => true]);

        // Act
        $this->patch('/admin/pages/' . $page->id . '/set-active-status', []);

        // Assert
        $page->refresh();
        $status->contains('stuff')
            ? $this->assertFalse($page->isActive())
            : $this->assertTrue($page->isActive());
    }
}
