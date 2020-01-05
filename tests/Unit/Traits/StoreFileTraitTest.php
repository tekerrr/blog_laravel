<?php

namespace Tests\Unit\Traits;

use App\Http\Requests\StoreFileTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithImage;
use Tests\WithMockForTrait;

class StoreFileTraitTest extends TestCase
{
    use WithMockForTrait, WithFaker, WithImage;

    /** @test */
    public function method_store_file_returns_path_to_saved_file_when_the_file_input_is_not_empty()
    {
        // Arrange
        $mock = $this->mockForTrait(StoreFileTrait::Class, ['has', 'file']);
        $field = $this->faker->word;

        $mock
            ->method('has')
            ->with($field)
            ->willReturn(true);
        $mock
            ->method('file')
            ->with($field)
            ->willReturn($this->getUploadedImage());

        // Act
        $response = $mock->storeFile('/public', $field);

        // Assert
        \Storage::disk('local')->assertExists($this->image = $response);
    }

    /** @test */
    public function method_store_file_returns_null_when_the_file_input_is_empty()
    {
        // Arrange
        $mock = $this->mockForTrait(StoreFileTrait::Class, ['has']);
        $field = $this->faker->word;

        $mock
            ->method('has')
            ->with($field)
            ->willReturn(false);

        // Act
        $response = $mock->storeFile('', $field);

        // Assert
        $this->assertNull($response);
    }
}
