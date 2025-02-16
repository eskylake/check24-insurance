<?php

declare(strict_types=1);

namespace App\Tests\Unit\Insurance\Application\UseCase;

use App\Tests\Unit\TestCase;
use App\Insurance\Domain\Exception\FieldDefinitionException;
use App\Insurance\Application\UseCase\MapInputToXMLRequestUseCase;
use App\Insurance\Domain\ValueObject\Mapping;
use App\FieldMapping\Domain\DataObject\{ProcessedField, MappedData as FieldMappedData};
use App\Insurance\Domain\Service\{ComputedFieldServiceInterface, RequestBuilderServiceInterface};
use App\FieldMapping\Domain\Service\{FieldMappingServiceInterface, XmlStructureBuilderServiceInterface};

class MapInputToXMLRequestUseCaseTest extends TestCase
{
    private FieldMappingServiceInterface $fieldMappingService;

    private ComputedFieldServiceInterface $computedFieldService;

    private XmlStructureBuilderServiceInterface $xmlStructureBuilderService;

    private RequestBuilderServiceInterface $requestBuilderService;

    private MapInputToXMLRequestUseCase $useCase;

    protected function setUp(): void
    {
        $this->fieldMappingService = $this->createMock(FieldMappingServiceInterface::class);
        $this->computedFieldService = $this->createMock(ComputedFieldServiceInterface::class);
        $this->xmlStructureBuilderService = $this->createMock(XmlStructureBuilderServiceInterface::class);
        $this->requestBuilderService = $this->createMock(RequestBuilderServiceInterface::class);

        $this->useCase = new MapInputToXMLRequestUseCase(
            $this->fieldMappingService,
            $this->computedFieldService,
            $this->xmlStructureBuilderService,
            $this->requestBuilderService,
        );
    }

    public function testExecuteWithNoComputedFieldsSuccessfully(): void
    {
        // Arrange
        $inputs = ['field1' => 'value1'];
        $mappings = [
            'root' => 'Request',
            'field_definitions' => [
                'field1' => [
                    'field' => 'field1',
                    'maps_to' => 'mapped_field1',
                ],
            ],
        ];

        $mapping = Mapping::fromArray($mappings);
        $mappedData = ['mapped_field1' => 'value1'];
        $fieldDefs = [
            $this->createFieldDefinition(
                field: 'field1',
                mapsTo: 'mapped_field1',
                xmlPath: ['Data/Field1'],
            ),
        ];

        $processedFields = new ProcessedField(
            $fieldDefs,
            new FieldMappedData($mappedData, []),
        );

        $structure = ['Data' => ['Field1' => ['mapped_field1' => 'value1']]];
        $expectedXml = '<?xml version="1.0"?><Request><Data><Field1>value1</Field1></Data></Request>';

        $this->fieldMappingService
            ->expects($this->once())
            ->method('processFields')
            ->with($inputs, $mappings['field_definitions'])
            ->willReturn($processedFields);

        $this->xmlStructureBuilderService
            ->expects($this->once())
            ->method('buildNestedArray')
            ->with($mappedData, $fieldDefs)
            ->willReturn($structure);

        $this->requestBuilderService
            ->expects($this->once())
            ->method('buildRequest')
            ->with($mapping, $structure)
            ->willReturn($expectedXml);

        // Act
        $result = $this->useCase->execute($inputs, $mappings);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testExecuteWithComputedFieldsSuccessfully(): void
    {
        // Arrange
        $inputs = [
            'base_amount' => 100,
            'tax_rate' => 0.2,
        ];
        $mappings = [
            'root' => 'Request',
            'field_definitions' => [
                'base_amount' => [
                    'field' => 'base_amount',
                    'maps_to' => 'amount',
                ],
                'tax' => [
                    'field' => 'tax',
                    'maps_to' => 'tax_amount',
                    'computed' => true,
                ],
            ],
        ];

        $mapping = Mapping::fromArray($mappings);
        $computedFields = [
            $this->createFieldDefinition(
                field: 'tax',
                mapsTo: 'tax_amount',
                computed: true,
                xmlPath: ['Data/Tax'],
            ),
        ];

        $mappedData = ['amount' => 100];
        $computedData = ['tax_amount' => 20];

        $processedFields = new ProcessedField(
            [$computedFields[0]],
            new FieldMappedData($mappedData, $computedFields),
        );

        $structure = ['Data' => ['Amount' => 100, 'Tax' => 20]];
        $expectedXml = '<?xml version="1.0"?><Request><Data><Amount>100</Amount><Tax>20</Tax></Data></Request>';

        $this->fieldMappingService
            ->expects($this->once())
            ->method('processFields')
            ->willReturn($processedFields);

        $this->computedFieldService
            ->expects($this->once())
            ->method('compute')
            ->with($computedFields, $inputs)
            ->willReturn($computedData);

        $this->xmlStructureBuilderService
            ->expects($this->once())
            ->method('buildNestedArray')
            ->willReturn($structure);

        $this->requestBuilderService
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturn($expectedXml);

        // Act
        $result = $this->useCase->execute($inputs, $mappings);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testExecuteWithInvalidMappingConfiguration(): void
    {
        // Arrange
        $inputs = ['field1' => 'value1'];
        $mappings = [
            'root' => 'Request',
        ];

        // Assert
        $this->expectException(FieldDefinitionException::class);

        // Act
        $this->useCase->execute($inputs, $mappings);
    }
}