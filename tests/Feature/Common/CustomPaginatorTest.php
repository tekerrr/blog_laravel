<?php

namespace Tests\Feature\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomPaginatorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function custom_paginator_can_add_a_query_string_to_the_empty_query_request()
    {
        // Arrange
        $this->actingAsRole('admin');
        $this->get($url = '/admin/articles');

        // Act
        $response = $this->patch('/per-page', ['items' => 20]);

        // Assert
        $response->assertRedirect($url . '?items=20');
    }

    /** @test */
    public function custom_paginator_can_add_a_query_string_to_the_query_request()
    {
        // Arrange
        $this->actingAsRole('admin');
        $this->get($url = '/admin/articles?test=test');

        // Act
        $response = $this->patch('/per-page', ['items' => 20]);

        // Assert
        $response->assertRedirect($url . '&items=20');
    }

    /** @test */
    public function custom_paginator_can_change_the_query_string()
    {
        // Arrange
        $this->actingAsRole('admin');
        $this->get('/admin/articles?items=10');

        // Act
        $response = $this->patch('/per-page', ['items' => 20]);

        // Assert
        $response->assertRedirect('/admin/articles?items=20');
    }
}
