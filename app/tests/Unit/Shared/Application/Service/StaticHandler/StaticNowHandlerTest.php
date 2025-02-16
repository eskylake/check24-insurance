<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\StaticHandler;

use DateTimeImmutable;
use App\Tests\Unit\TestCase;
use App\Shared\Application\Service\StaticHandler\StaticNowHandler;

class StaticNowHandlerTest extends TestCase
{
    private StaticNowHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new StaticNowHandler();
    }

    public function testHandleWithDefaultFormat(): void
    {
        // Arrange
        $expectedFormat = 'Y-m-d\\T00:00:00';
        $expected = (new DateTimeImmutable())->format($expectedFormat);

        // Act
        $result = $this->handler->handle();

        // Assert
        $this->assertEquals($expected, $result);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T00:00:00$/', $result);
    }

    public function testHandleWithCustomDateFormat(): void
    {
        // Arrange
        $format = 'Y-m-d';
        $expected = (new DateTimeImmutable())->format($format);

        // Act
        $result = $this->handler->handle(['format' => $format]);

        // Assert
        $this->assertEquals($expected, $result);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result);
    }
}