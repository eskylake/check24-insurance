<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldMapping\Application\Service;

use App\Tests\Unit\TestCase;
use App\FieldMapping\Application\Service\FieldStaticService;
use App\Shared\Infrastructure\StaticHandler\StaticHandlerFactory;

class FieldStaticServiceTest extends TestCase
{
    private StaticHandlerFactory $staticHandlerFactory;

    private FieldStaticService $staticService;

    protected function setUp(): void
    {
        $this->staticHandlerFactory = $this->createMock(StaticHandlerFactory::class);
        $this->staticService = new FieldStaticService($this->staticHandlerFactory);
    }

    public function testHandleInputWithSimpleStaticValue(): void
    {
        // Arrange
        $fieldDef = $this->createFieldDefinition(
            field: 'name',
            mapsTo: 'dummy_name',
            static: 'Ali',
            xmlPath: ['Dummy/Name'],
        );

        $this->staticHandlerFactory
            ->expects($this->once())
            ->method('getHandler')
            ->with('Ali')
            ->willReturn(null);

        // Act
        $result = $this->staticService->handleInput($fieldDef);

        // Assert
        $this->assertEquals('Ali', $result);
    }

    public function testHandleInputWithUnknownStaticValue(): void
    {
        // Arrange
        $fieldDef = $this->createFieldDefinition(
            field: 'dummy_type',
            mapsTo: 'type',
            static: 'STANDARD',
            xmlPath: ['Dummy/Type'],
        );

        // Act
        $result = $this->staticService->handleInput($fieldDef);

        // Assert
        $this->assertEquals('STANDARD', $result);
    }

    public function testHandleOutputWithUnknownStaticValue(): void
    {
        // Arrange
        $fieldDef = $this->createFieldDefinition(
            field: 'category',
            mapsTo: 'dummy_category',
            static: 'TEST',
            xmlPath: ['Dummy/Category'],
        );

        // Act
        $result = $this->staticService->handleOutput($fieldDef);

        // Assert
        $this->assertEquals('TEST', $result);
    }
}