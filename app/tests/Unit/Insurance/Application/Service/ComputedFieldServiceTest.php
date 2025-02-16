<?php

declare(strict_types=1);

namespace App\Tests\Unit\Insurance\Application\Service;

use App\Tests\Unit\TestCase;
use BadMethodCallException;
use App\Insurance\Application\Service\ComputedFieldService;
use App\FieldMapping\Domain\Exception\FieldMapperException;

class ComputedFieldServiceTest extends TestCase
{
    private ComputedFieldService $computedFieldService;

    protected function setUp(): void
    {
        $this->computedFieldService = new ComputedFieldService();
    }

    public function testComputeOccasionalDriverWithSiValue(): void
    {
        // Arrange
        $inputs = ['occasionalDriver' => 'SI'];
        $fieldDef = $this->createFieldDefinition(
            field: 'occasionalDriver',
            mapsTo: 'driver_flag',
            computed: true,
            xmlPath: ['Data/Driver/Flag'],
        );

        // Act
        $result = $this->computedFieldService->compute([$fieldDef], $inputs);

        // Assert
        $this->assertArrayHasKey('driver_flag', $result);
        $this->assertEquals(1, $result['driver_flag']);
    }

    public function testComputeOccasionalDriverWithNoValue(): void
    {
        // Arrange
        $inputs = ['occasionalDriver' => 'NO'];
        $fieldDef = $this->createFieldDefinition(
            field: 'occasionalDriver',
            mapsTo: 'driver_flag',
            computed: true,
            xmlPath: ['Data/Driver/Flag'],
        );

        // Act
        $result = $this->computedFieldService->compute([$fieldDef], $inputs);

        // Assert
        $this->assertArrayHasKey('driver_flag', $result);
        $this->assertEquals(0, $result['driver_flag']);
    }

    public function testComputeOccasionalDriverWithMissingField(): void
    {
        // Arrange
        $inputs = ['otherField' => 'value'];
        $fieldDef = $this->createFieldDefinition(
            field: 'occasionalDriver',
            mapsTo: 'driver_flag',
            computed: true,
            xmlPath: ['Data/Driver/Flag'],
        );

        // Act
        $result = $this->computedFieldService->compute([$fieldDef], $inputs);

        // Assert
        $this->assertArrayHasKey('driver_flag', $result);
        $this->assertEquals(0, $result['driver_flag']);
    }

    public function testComputeWithInvalidFieldDefinition(): void
    {
        // Arrange
        $inputs = ['occasionalDriver' => 'SI'];
        $invalidDef = new \stdClass();

        // Assert
        $this->expectException(FieldMapperException::class);

        // Act
        $this->computedFieldService->compute([$invalidDef], $inputs);
    }

    public function testComputeWithNonexistentComputeMethod(): void
    {
        // Arrange
        $inputs = ['nonexistentField' => 'value'];
        $fieldDef = $this->createFieldDefinition(
            field: 'nonexistentField',
            mapsTo: 'computed_value',
            computed: true,
            xmlPath: ['Data/Value'],
        );

        // Assert
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method (computeNonexistentField) not implemented');

        // Act
        $this->computedFieldService->compute([$fieldDef], $inputs);
    }

    public function testComputeWithEmptyComputedFields(): void
    {
        // Arrange
        $inputs = ['someField' => 'value'];

        // Act
        $result = $this->computedFieldService->compute([], $inputs);

        // Assert
        $this->assertEmpty($result);
    }
}