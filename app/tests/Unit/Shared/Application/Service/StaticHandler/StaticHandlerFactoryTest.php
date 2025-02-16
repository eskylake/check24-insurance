<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\StaticHandler;

use App\Tests\Unit\TestCase;
use App\Shared\Application\Service\StaticHandler\{
    StaticHandlerFactory,
    StaticNowHandler
};

class StaticHandlerFactoryTest extends TestCase
{
    private StaticNowHandler $staticNowHandler;

    private StaticHandlerFactory $factory;

    protected function setUp(): void
    {
        $this->staticNowHandler = $this->createMock(StaticNowHandler::class);
        $this->factory = new StaticHandlerFactory($this->staticNowHandler);
    }

    public function testGetHandlerWithNowValue(): void
    {
        // Arrange
        $staticValue = 'now';

        // Act
        $result = $this->factory->getHandler($staticValue);

        // Assert
        $this->assertSame($this->staticNowHandler, $result);
    }
}