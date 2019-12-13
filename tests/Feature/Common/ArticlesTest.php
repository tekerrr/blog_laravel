<?php

namespace Tests\Feature\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithRoles;

class ArticlesTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithRoles;

    /** @test */
    public function anyone_can_view_the_article_list_page()
    {
        // Act
        $response = $this->get('/articles');

        // Assert
        $response->assertViewIs('articles.index');
        $response->assertSeeText('Главная');
    }
}
