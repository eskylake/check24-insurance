<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldMapping\Application\Service;

use PHPUnit\Framework\TestCase;
use App\FieldMapping\Domain\ValueObject\XmlPath;
use App\FieldMapping\Application\Service\FieldMapperService;
use App\FieldMapping\Domain\DataObject\MappedData;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;

class FieldMapperServiceTest extends TestCase
{
    private FieldStaticServiceInterface $fieldStaticService;

    private FieldMapperService $fieldMapperService;

    protected function setUp(): void
    {
        $this->fieldStaticService = $this->createMock(FieldStaticServiceInterface::class);
        $this->fieldMapperService = new FieldMapperService($this->fieldStaticService);
    }

    public function testMapWithSimpleFieldMapping(): void
    {
        // Arrange
        $inputData = ['name' => 'Ali'];
        $fieldDefinition = $this->createFieldDefinition('name', 'dummy_name');

        // Act
        $result = $this->fieldMapperService->map($inputData, [$fieldDefinition]);

        // Assert
        $this->assertInstanceOf(MappedData::class, $result);
        $this->assertEquals(['dummy_name' => 'Ali'], $result->getMapped());
        $this->assertEmpty($result->getComputed());
    }

    public function testMapWithComputedField(): void
    {
        // Arrange
        $inputData = ['name' => 'Ali'];
        $fieldDefinition = $this->createFieldDefinition(
            field: 'name',
            mapsTo: 'dummy_name',
            computed: true
        );

        // Act
        $result = $this->fieldMapperService->map($inputData, [$fieldDefinition]);

        // Assert
        $this->assertEmpty($result->getMapped());
        $this->assertCount(1, $result->getComputed());
        $this->assertSame($fieldDefinition, $result->getComputed()[0]);
    }

    public function testMapWithStaticValue(): void
    {
        // Arrange
        $inputData = [];
        $fieldDefinition = $this->createFieldDefinition(
            field: 'type',
            mapsTo: 'dummy_type',
            static: 'regular'
        );

        $this->fieldStaticService
            ->expects($this->once())
            ->method('handleOutput')
            ->with($fieldDefinition)
            ->willReturn('regular');

        // Act
        $result = $this->fieldMapperService->map($inputData, [$fieldDefinition]);

        // Assert
        $this->assertEquals(['dummy_type' => 'regular'], $result->getMapped());
    }

    public function testMapWithValueMapping(): void
    {
        // Arrange
        $inputData = ['status' => 'A'];
        $fieldDefinition = $this->createFieldDefinition(
            field: 'status',
            mapsTo: 'dummy_status',
            values: ['A' => 'Active', 'I' => 'Inactive']
        );

        // Act
        $result = $this->fieldMapperService->map($inputData, [$fieldDefinition]);

        // Assert
        $this->assertEquals(['dummy_status' => 'Active'], $result->getMapped());
    }

    public function testMapWithMissingFieldAndNoStatic(): void
    {
        // Arrange
        $inputData = [];
        $fieldDefinition = $this->createFieldDefinition(
            field: 'missing_field',
            mapsTo: 'target_field'
        );

        // Act
        $result = $this->fieldMapperService->map($inputData, [$fieldDefinition]);

        // Assert
        $this->assertEmpty($result->getMapped());
        $this->assertEmpty($result->getComputed());
    }

    public function testMapWithInvalidFieldDefinition(): void
    {
        // Arrange
        $inputData = ['name' => 'Ali'];
        $invalidDefinition = new \stdClass();

        // Assert
        $this->expectException(FieldMapperException::class);

        // Act
        $this->fieldMapperService->map($inputData, [$invalidDefinition]);
    }

    public function testMapWithMultipleFields(): void
    {
        // Arrange
        $inputData = [
            'name' => 'Ali',
            'age' => 26,
            'status' => 'A'
        ];

        $fieldDefinitions = [
            $this->createFieldDefinition(
                field: 'name',
                mapsTo: 'dummy_name'
            ),
            $this->createFieldDefinition(
                field: 'age',
                mapsTo: 'dummy_age'
            ),
            $this->createFieldDefinition(
                field: 'status',
                mapsTo: 'dummy_status',
                values: ['A' => 'Active', 'I' => 'Inactive']
            )
        ];

        // Act
        $result = $this->fieldMapperService->map($inputData, $fieldDefinitions);

        // Assert
        $expected = [
            'dummy_name' => 'Ali',
            'dummy_age' => 26,
            'dummy_status' => 'Active'
        ];
        $this->assertEquals($expected, $result->getMapped());
    }

    private function createFieldDefinition(
        string $field,
        string $mapsTo,
        bool $required = false,
        array $validation = [],
        mixed $static = null,
        bool $computed = false,
        ?array $xmlPath = null,
        ?array $values = null,
        ?string $description = null,
    ): FieldDefinition {
        return new FieldDefinition(
            field: $field,
            mapsTo: $mapsTo,
            required: $required,
            validation: $validation,
            static: $static,
            computed: $computed,
            xmlPath: XmlPath::fromArray($xmlPath ?: ['sample/dummy']),
            values: $values,
            description: $description,
        );
    }
}