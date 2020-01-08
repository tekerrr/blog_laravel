<?php

namespace Tests\Unit;

use App\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function method_set_content_can_create_new_settings()
    {
        // Arrange
        $settings = factory(Settings::class)->make();

        // Act
        Settings::setContent($settings->name, $settings->value);

        // Assert
        $this->assertDatabaseHas($settings->getTable(), ['name' => 'content.' . $settings->name, 'value' => $settings->value]);
    }

    /** @test */
    public function method_set_content_can_update_settings()
    {
        // Arrange
        $settings = factory(Settings::class)->create(['name' => 'content.' . $this->faker->word]);
        $newValue = $this->faker->word;

        // Act
        Settings::setContent($settings->name, $newValue);

        // Assert
        $this->assertDatabaseHas($settings->getTable(), ['name' => $settings->name, 'value' => $newValue]);
        $this->assertDatabaseMissing($settings->getTable(), ['name' => $settings->name, 'value' => $settings->value]);
    }

    /** @test */
    public function method_set_content_add_prefix_to_the_name_without_prefix()
    {
        // Arrange
        $name = $this->faker->word;

        // Act
        Settings::setContent($name, $this->faker->word);

        // Assert
        $this->assertEquals('content.' . $name, Settings::first()->name);
    }

    /** @test */
    public function method_set_content_doesnt_add_prefix_to_the_name_with_prefix()
    {
        // Arrange
        $name = 'content.' . $this->faker->word;

        // Act
        Settings::setContent($name, $this->faker->word);

        // Assert
        $this->assertEquals($name, Settings::first()->name);
    }
}
