<?php

namespace Tests\Feature\RequestValidation;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\WithImage;

class UpdateAvatar extends TestCase
{
    use RefreshDatabase, WithFaker, WithImage;

    public function invalidDataProvider()
    {
        $faker = Factory::create();

        return [
            'empty_avatar'       => ['avatar', ''],
            'not_a_file_avatar'  => ['avatar', ['array']],
            'not_a_image_avatar' => ['avatar', UploadedFile::fake()->create('new_file.txt')],
            'big_size_avatar'    => ['avatar', UploadedFile::fake()->create('new_avatar.jpg', 2100)],
        ];
    }

    /** @test */
    public function the_valid_data_passes_the_validation_rules()
    {
        // Arrange
        $user = $this->actingAsUser();

        // Act
        $attributes['avatar'] = $this->getUploadedImage();
        $response = $this->patch('/avatar', $attributes);
        $this->image = $user->image->path;

        // Assert
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     * @param string $field
     * @param $value
     */
    public function the_invalid_data_fails_the_validation_rules(string $field, $value)
    {
        // Arrange
        $this->actingAsUser();

        // Act
        $attributes[$field] = $value;
        $response = $this->patch('/avatar', $attributes);

        // Assert
        $response->assertSessionHasErrors([$field]);
    }
}
