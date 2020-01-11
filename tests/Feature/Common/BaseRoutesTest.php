<?php

namespace Tests\Feature\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function main_redirect()
    {
        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(302);
    }
}
