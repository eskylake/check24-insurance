<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldMapping\Application\Service;

use App\Tests\Unit\TestCase;
use App\FieldMapping\Application\Service\FieldMappingService;
use App\FieldMapping\Domain\DataObject\{ProcessedField, MappedData};
use App\FieldMapping\Domain\ValueObject\{FieldDefinition, XmlPath};
use App\FieldMapping\Domain\Service\{FieldValidatorServiceInterface, FieldMapperServiceInterface};
use App\FieldMapping\Domain\Exception\{FieldValidationException, FieldMapperException};

class FieldMappingServiceTest extends TestCase
{
    private FieldValidatorServiceInterface $validator;

    private FieldMapperServiceInterface $mapper;

    private FieldMappingService $mappingService;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(FieldValidatorServiceInterface::class);
        $this->mapper = $this->createMock(FieldMapperServiceInterface::class);
        $this->mappingService = new FieldMappingService($this->validator, $this->mapper);
    }

    public function testProcessFieldsSuccessfully(): void
    {
        // Arrange
        $inputs = [
            'name' => 'Ali',
            'code' => 'ABC123'
        ];

        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_name',
                'xml_path' => ['Dummy/Name']
            ],
            'code' => [
                'field' => 'code',
                'maps_to' => 'dummy_code',
                'xml_path' => ['Dummy/Code']
            ]
        ];

        $validatedFields = [
            $this->createFieldDefinition(
                field: 'name',
                mapsTo: 'dummy_name',
                xmlPath: ['Dummy/Name']
            ),
            $this->createFieldDefinition(
                field: 'code',
                mapsTo: 'dummy_code',
                xmlPath: ['Dummy/Code']
            )
        ];

        $mappedData = new MappedData(
            mapped: [
                'dummy_name' => 'Ali',
                'dummy_code' => 'ABC123'
            ],
            computed: []
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($inputs, $fieldDefinitions)
            ->willReturn($validatedFields);

        $this->mapper
            ->expects($this->once())
            ->method('map')
            ->with($inputs, $validatedFields)
            ->willReturn($mappedData);

        // Act
        $result = $this->mappingService->processFields($inputs, $fieldDefinitions);

        // Assert
        $this->assertInstanceOf(ProcessedField::class, $result);
        $this->assertEquals($validatedFields, $result->getFieldDefs());
        $this->assertEquals($mappedData, $result->getMappedData());
    }

    public function testProcessFieldsWithValidationException(): void
    {
        // Arrange
        $inputs = ['invalid_field' => 'value'];
        $fieldDefinitions = [
            'invalid_field' => [
                'field' => 'invalid_field',
                'maps_to' => 'dummy_invalid',
                'required' => true,
                'xml_path' => ['path' => 'Dummy/Invalid']
            ]
        ];

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($inputs, $fieldDefinitions)
            ->willThrowException(new FieldValidationException(['invalid_field' => ['Field is invalid']]));

        // Assert
        $this->expectException(FieldValidationException::class);

        // Act
        $this->mappingService->processFields($inputs, $fieldDefinitions);
    }

    public function testProcessFieldsWithMapperException(): void
    {
        // Arrange
        $inputs = ['name' => 'Ali'];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_name',
                'xml_path' => ['Dummy/Name'],
            ]
        ];

        $validatedFields = [
            $this->createFieldDefinition(
                field: 'name',
                mapsTo: 'dummy_name',
                xmlPath: ['Dummy/Name'],
            )
        ];

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($inputs, $fieldDefinitions)
            ->willReturn($validatedFields);

        $this->mapper
            ->expects($this->once())
            ->method('map')
            ->with($inputs, $validatedFields)
            ->willThrowException(new FieldMapperException());

        // Assert
        $this->expectException(FieldMapperException::class);

        // Act
        $this->mappingService->processFields($inputs, $fieldDefinitions);
    }
}