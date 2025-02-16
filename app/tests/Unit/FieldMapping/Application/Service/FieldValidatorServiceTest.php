<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldMapping\Application\Service;

use App\Tests\Unit\TestCase;
use App\FieldMapping\Domain\ValueObject\{FieldDefinition};
use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;
use App\Shared\Application\Service\Validator\ValidatorFactory;
use App\FieldMapping\Domain\Exception\FieldValidationException;
use App\FieldMapping\Application\Service\FieldValidatorService;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;

class FieldValidatorServiceTest extends TestCase
{
    private FieldStaticServiceInterface $fieldStaticService;

    private ValidatorFactory $validatorFactory;

    private FieldValidatorService $validatorService;

    private ValidatorInterface $typeValidator;

    protected function setUp(): void
    {
        $this->fieldStaticService = $this->createMock(FieldStaticServiceInterface::class);
        $this->validatorFactory = $this->createMock(ValidatorFactory::class);
        $this->typeValidator = $this->createMock(ValidatorInterface::class);

        $this->validatorService = new FieldValidatorService(
            $this->fieldStaticService,
            $this->validatorFactory
        );
    }

    public function testValidateWithValidField(): void
    {
        // Arrange
        $data = ['name' => 'Ali'];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_name',
                'xml_path' => ['path' => 'Dummy/Name']
            ]
        ];

        // Act
        $result = $this->validatorService->validate($data, $fieldDefinitions);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(FieldDefinition::class, $result[0]);
        $this->assertEquals('name', $result[0]->getField());
    }

    public function testValidateWithRequiredFieldMissing(): void
    {
        // Arrange
        $data = [];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_name',
                'required' => true,
                'xml_path' => ['path' => 'Dummy/Name']
            ]
        ];

        // Assert
        $this->expectException(FieldValidationException::class);

        // Act
        $this->validatorService->validate($data, $fieldDefinitions);
    }

    public function testValidateWithTypeValidation(): void
    {
        // Arrange
        $data = ['name' => 'Ali'];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_age',
                'validation' => [
                    'type' => 'string',
                ],
                'xml_path' => ['path' => 'Dummy/Name']
            ]
        ];

        $this->validatorFactory
            ->expects($this->once())
            ->method('getValidator')
            ->with('string')
            ->willReturn($this->typeValidator);

        $this->typeValidator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(ValidationResult::valid());

        // Act
        $result = $this->validatorService->validate($data, $fieldDefinitions);

        // Assert
        $this->assertCount(1, $result);
    }

    public function testValidateWithInvalidTypeValidation(): void
    {
        // Arrange
        $data = ['name' => 1];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_age',
                'validation' => [
                    'type' => 'string',
                ],
                'xml_path' => ['path' => 'Dummy/Name']
            ]
        ];

        $this->validatorFactory
            ->expects($this->once())
            ->method('getValidator')
            ->with('string')
            ->willReturn($this->typeValidator);

        $this->typeValidator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(ValidationResult::invalid(['Must be a string']));

        // Assert
        $this->expectException(FieldValidationException::class);

        // Act
        $this->validatorService->validate($data, $fieldDefinitions);
    }

    public function testValidateWithStaticValue(): void
    {
        // Arrange
        $data = [];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_type',
                'static' => 'regular',
                'xml_path' => ['path' => 'Dummy/Name']
            ]
        ];

        $this->fieldStaticService
            ->expects($this->once())
            ->method('handleInput')
            ->willReturn('regular');

        // Act
        $result = $this->validatorService->validate($data, $fieldDefinitions);

        // Assert
        $this->assertCount(1, $result);
    }

    public function testValidateWithInvalidValueMapping(): void
    {
        // Arrange
        $data = ['status' => 'X'];
        $fieldDefinitions = [
            'status' => [
                'field' => 'status',
                'maps_to' => 'dummy_status',
                'values' => ['A' => 'Active', 'I' => 'Inactive'],
                'xml_path' => ['path' => 'Dummy/Status']
            ]
        ];

        // Assert
        $this->expectException(FieldValidationException::class);

        // Act
        $this->validatorService->validate($data, $fieldDefinitions);
    }

    public function testValidateWithComputedField(): void
    {
        // Arrange
        $data = ['total' => 100];
        $fieldDefinitions = [
            'tax' => [
                'field' => 'total',
                'maps_to' => 'dummy_total',
                'computed' => true,
                'xml_path' => ['path' => 'Dummy/Total']
            ]
        ];

        // Act
        $result = $this->validatorService->validate($data, $fieldDefinitions);

        // Assert
        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->isComputed());
    }

    public function testValidateWithMissingXmlPath(): void
    {
        // Arrange
        $data = ['name' => 'Ali'];
        $fieldDefinitions = [
            'name' => [
                'field' => 'name',
                'maps_to' => 'dummy_name'
            ]
        ];

        // Act
        $result = $this->validatorService->validate($data, $fieldDefinitions);

        // Assert
        $this->assertEmpty($result);
    }
}