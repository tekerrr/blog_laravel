<?php

namespace Tests\Unit;

use App\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_class_is_using_can_be_activated_trait_correctly()
    {
        // Arrange
        $elements = factory(Page::class, 2)->create(['is_active' => false]);

        // Act
        $elements->first()->activate();

        // Assert
        $this->assertTrue($elements->first()->isActive());
        $this->assertFalse($elements->last()->isActive());
    }
}
