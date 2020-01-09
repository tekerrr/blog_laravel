<?php

namespace Tests\Unit\Traits;

use App\Http\Requests\ParagraphTagTrait;
use Tests\TestCase;
use Tests\WithMockForTrait;

class ParagraphTagTraitTest extends TestCase
{
    use WithMockForTrait;

    /** @test */
    public function method_tag_returns_the_formatted_string_when_the_paragraph_tag_input_exists()
    {
        // Arrange
        $mock = $this->mockForTrait(ParagraphTagTrait::Class, ['has']);

        $mock
            ->method('has')
            ->with('paragraph_tag')
            ->willReturn(true);

        // Act
        $response = $mock->tag('test');

        // Assert
        $this->assertEquals('<p>test</p>', $response);
    }

    /** @test */
    public function method_tag_returns_the_original_string_when_the_paragraph_tag_input_not_exists()
    {
        // Arrange
        $mock = $this->mockForTrait(ParagraphTagTrait::Class, ['has']);

        $mock
            ->method('has')
            ->with('paragraph_tag')
            ->willReturn(false);

        // Act
        $response = $mock->tag('test');

        // Assert
        $this->assertEquals('test', $response);
    }
}
