<?php

namespace Tests\Unit;

use App\Service\CustomPaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomPaginatorTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function custom_paginator_without_request_returns_default_per_page()
    {
        // Arrange
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->getPerPage();

        // Assert
        $this->assertEquals(config('content.custom_paginator.items'), $response);
    }

    /** @test */
    public function custom_paginator_with_correct_request_returns_correct_per_page()
    {
        // Arrange
        request()->merge(['items' => 20]);
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->getPerPage();

        // Assert
        $this->assertEquals(20, $response);
    }

    /** @test */
    public function custom_paginator_with_incorrect_request_returns_default_per_page()
    {
        // Arrange
        request()->merge(['items' => 21]);
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->getPerPage();

        // Assert
        $this->assertEquals(config('content.custom_paginator.items'), $response);
    }

    /** @test */
    public function custom_paginator_returns_query()
    {
        // Arrange
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->getQuery();

        // Assert
        $this->assertEquals(['items' => config('content.custom_paginator.items')], $response);
    }

    /** @test */
    public function custom_paginator_returns_selector_options()
    {
        // Arrange
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->getSelectorOptions();

        // Assert
        $this->assertEquals(config('content.custom_paginator.options'), $response);
    }

    /** @test */
    public function method_paginate_returns_paginator_when_paginator_is_necessary()
    {
        // Arrange
        factory(\App\Article::class, 33)->create();
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->paginate(\App\Article::where('id', '>=', 1));

        // Assert
        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($response));
        $this->assertEquals(config('content.custom_paginator.items'), $response->count());
    }

    /** @test */
    public function method_paginate_returns_paginator_without_links_with_items_query_when_is_default()
    {
        // Arrange
        factory(\App\Article::class, 2)->create();
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->paginate(\App\Article::where('id', '>=', 1));

        // Assert
        $this->assertFalse(Str::contains($response->url(2), 'items='));
    }

    /** @test */
    public function method_paginate_returns_paginator_with_links_with_items_query_when_is_not_default()
    {
        // Arrange
        factory(\App\Article::class, 2)->create();
        request()->merge(['items' => 20]);
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->paginate(\App\Article::where('id', '>=', 1));

        // Assert
        $this->assertTrue(Str::contains($response->url(2), 'items=20'));
    }

    /** @test */
    public function method_paginate_returns_collection_with_all_items_when_paginator_is_not_necessary()
    {
        // Arrange
        factory(\App\Article::class, 33)->create();
        request()->merge(['items' => 'all']);
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->paginate(\App\Article::where('id', '>=', 1));

        // Assert
        $this->assertEquals('Illuminate\Database\Eloquent\Collection', get_class($response));
        $this->assertEquals(\App\Article::count(), $response->count());
    }

    /** @test */
    public function method_is_need_returns_true_when_paginator_is_necessary()
    {
        // Arrange
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->isNeed();

        // Assert
        $this->assertTrue($response);
    }

    /** @test */
    public function method_is_need_returns_false_when_paginator_is_not_necessary()
    {
        // Arrange
        request()->merge(['items' => 'all']);
        $paginator = new CustomPaginator();

        // Act
        $response = $paginator->isNeed();

        // Assert
        $this->assertFalse($response);
    }
}
