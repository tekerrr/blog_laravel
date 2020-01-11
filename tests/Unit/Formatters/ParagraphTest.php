<?php

namespace Tests\Unit\Formatters;

use App\Service\Formatter\Paragraph;
use Tests\TestCase;

class ParagraphTest extends TestCase
{
    public function textProvider()
    {
        return [
            [
                'text',
                '<p>text</p>',
            ],
            [
                '<p>text</p>',
                '<p>text</p>',
            ],
            [
                'text</p>',
                '<p>text</p>',
            ],
            [
                '<p>text',
                '<p>text</p>',
            ],
            [
                '<p>text</p><p>text</p>',
                '<p>text</p><p>text</p>',
            ],
            [
                'text' . PHP_EOL . 'text',
                '<p>text</p>' . PHP_EOL . '<p>text</p>',
            ],
            [
                "text\r\ntext",
                '<p>text</p>' . PHP_EOL . '<p>text</p>',
            ],
            [
                '<p>text</p>' . PHP_EOL . '<p>text</p>',
                '<p>text</p>' . PHP_EOL . '<p>text</p>',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider textProvider
     * @param string $text
     * @param string $expected
     */
    public function formatter_returns_russian_date($text, $expected)
    {
        // Arrange
        $formatter = new Paragraph();

        // Act
        $response = $formatter->format($text);

        // Assert
        $this->assertEquals($expected, $response);
    }
}
