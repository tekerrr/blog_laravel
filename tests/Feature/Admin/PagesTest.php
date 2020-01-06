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
    use RefreshDatabase, WithFaker, WithImage;

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
        if ($status->contains('stuff')) {
            $response
                ->assertViewIs('admin.pages.index')
                ->assertSeeText('Статичные страницы');
        } elseif ($status->contains('auth')) {
            $response->assertStatus(403);
        } else {
            $response->assertRedirect('/login');
        }
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
        if ($status->contains('stuff')) {
            $response
                ->assertViewIs('admin.pages.show')
                ->assertSeeText($page->body);
        } elseif ($status->contains('auth')) {
            $response->assertStatus(403);
        } else {
            $response->assertRedirect('/login');
        }
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
        if ($status->contains('stuff')) {
            $response
                ->assertViewIs('admin.pages.create')
                ->assertSeeText('Добавить статичную страницу');
        } elseif ($status->contains('auth')) {
            $response->assertStatus(403);
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
    public function only_stuff_can_create_a_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = factory(Page::class)->raw();

        // Act
        $this->post('/admin/pages', $attributes);

        // Assert
        if ($status->contains('stuff')) {
            $this->assertDatabaseHas(app(Page::class)->getTable(), ['body' => $attributes['body']]);
        } else {
            $this->assertDatabaseMissing(app(Page::class)->getTable(), ['body' => $attributes['body']]);
        }
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
        if ($status->contains('stuff')) {
            $response
                ->assertViewIs('admin.pages.edit')
                ->assertSeeText('Редактировать статичную страницу');
        } elseif ($status->contains('auth')) {
            $response->assertStatus(403);
        } else {
            $response->assertRedirect('/login');
        }
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
        if ($status->contains('stuff')) {
            $this->assertDatabaseHas(app(Page::class)->getTable(), $attributes);
        } else {
            $this->assertDatabaseMissing(app(Page::class)->getTable(), $attributes);
        }
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
        if ($status->contains('stuff')) {
            $this->assertDatabaseMissing(app(Page::class)->getTable(), ['body' => $page->body]);
        } else {
            $this->assertDatabaseHas(app(Page::class)->getTable(), ['body' => $page->body]);
        }
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
        if ($status->contains('stuff')) {
            $page->refresh();
            $this->assertTrue($page->isActive());
        } else {
            $this->assertFalse($page->isActive());
        }
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
        if ($status->contains('stuff')) {
            $page->refresh();
            $this->assertFalse($page->isActive());
        } else {
            $this->assertTrue($page->isActive());
        }
    }
}
