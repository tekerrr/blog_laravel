<?php


namespace Tests\Unit\Formatters;

use App\Service\Formatter\RusDate;
use Tests\TestCase;

class RusDateTest extends TestCase
{
    /** @test */
    public function formatter_returns_russian_date()
    {
        // Arrange
        $formatter = new RusDate();
        $date = \Carbon\Carbon::create(2000, 1, 1, 0, 0, 0);

        // Act
        $response = $formatter->format($date);

        // Assert
        $this->assertEquals('1 января 2000 г. 00:00', $response);
    }
}
