<?php

namespace Tests\Feature\Admin;

use App\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_view_the_settings_page($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);

        // Act
        $response = $this->get('/admin/settings');

        // Assert
        $status->contains('admin')
            ? $response
                ->assertViewIs('admin.settings.edit')
                ->assertSeeText('Настройки')
            : $response
                ->assertDontSeeText('Настройки');
    }

    /**
     * @test
     * @dataProvider visitorProvider
     * @param string $role
     * @param Collection $status
     */
    public function only_admin_can_update_settings($role, $status)
    {
        // Arrange
        $this->actingAsRole($role);
        $attributes = [
            'paginator_items' => $this->faker->numberBetween(1, 5),
            'navbar_articles' => $this->faker->numberBetween(1, 5),
            'custom_paginator_items' => $this->faker->randomElement(['10', '20', '50', '200', 'all']),
        ];


        // Act
        $this->patch('/admin/settings', $attributes);

        // Assert
        $status->contains('admin')
            ? $this
                ->assertDatabaseHas(app(Config::class)->getTable(),
                    ['key' => 'content.paginator.items' , 'value' => $attributes['paginator_items']])
            : $this
                ->assertDatabaseMissing(app(Config::class)->getTable(),
                    ['key' => 'content.paginator.items' , 'value' => $attributes['paginator_items']]);
    }
}
