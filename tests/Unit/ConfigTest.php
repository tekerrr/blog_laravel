<?php

namespace Tests\Unit;

use App\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function method_set_content_can_create_new_settings()
    {
        // Arrange
        $settings = factory(Config::class)->make();

        // Act
        Config::setContent($settings->key, $settings->value);

        // Assert
        $this->assertDatabaseHas($settings->getTable(), ['key' => 'content.' . $settings->key, 'value' => $settings->value]);
    }

    /** @test */
    public function method_set_content_can_update_settings()
    {
        // Arrange
        $settings = factory(Config::class)->create(['key' => 'content.' . $this->faker->word]);
        $newValue = $this->faker->word;

        // Act
        Config::setContent($settings->key, $newValue);

        // Assert
        $this->assertDatabaseHas($settings->getTable(), ['key' => $settings->key, 'value' => $newValue]);
        $this->assertDatabaseMissing($settings->getTable(), ['key' => $settings->key, 'value' => $settings->value]);
    }

    /** @test */
    public function method_set_content_add_prefix_to_the_name_without_prefix()
    {
        // Arrange
        $key = $this->faker->word;

        // Act
        Config::setContent($key, $this->faker->word);

        // Assert
        $this->assertEquals('content.' . $key, Config::first()->key);
    }

    /** @test */
    public function method_set_content_doesnt_add_prefix_to_the_name_with_prefix()
    {
        // Arrange
        $key = 'content.' . $this->faker->word;

        // Act
        Config::setContent($key, $this->faker->word);

        // Assert
        $this->assertEquals($key, Config::first()->key);
    }
}
