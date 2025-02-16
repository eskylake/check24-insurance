<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldMapping\Application\Service;

use App\Tests\Unit\TestCase;
use App\FieldMapping\Application\Service\XmlStructureBuilderService;
use App\FieldMapping\Domain\ValueObject\{FieldDefinition, XmlPath};
use App\FieldMapping\Domain\Exception\FieldMapperException;

class XmlStructureBuilderServiceTest extends TestCase
{
    private XmlStructureBuilderService $structureBuilder;

    protected function setUp(): void
    {
        $this->structureBuilder = new XmlStructureBuilderService();
    }

    public function testBuildNestedArrayWithSinglePath(): void
    {
        // Arrange
        $mappedData = ['mapped_code' => 'ABC123'];
        $fieldDefinitions = [
            $this->createFieldDefinition(
                field: 'code',
                mapsTo: 'mapped_code',
                xmlPath: ['Sample/Data/Code'],
            )
        ];

        // Act
        $result = $this->structureBuilder->buildNestedArray($mappedData, $fieldDefinitions);

        // Assert
        $expected = [
            'Sample' => [
                'Data' => [
                    'Code' => [
                        'mapped_code' => 'ABC123'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testBuildNestedArrayWithMultiplePathsForSameField(): void
    {
        // Arrange
        $mappedData = ['mapped_value' => 'TEST'];
        $fieldDefinitions = [
            $this->createFieldDefinition(
                field: 'value',
                mapsTo: 'mapped_value',
                xmlPath: ['Path1/Data', 'Path2/Data'],
            )
        ];

        // Act
        $result = $this->structureBuilder->buildNestedArray($mappedData, $fieldDefinitions);

        // Assert
        $expected = [
            'Path1' => [
                'Data' => [
                    'mapped_value' => 'TEST'
                ]
            ],
            'Path2' => [
                'Data' => [
                    'mapped_value' => 'TEST'
                ]
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testBuildNestedArrayWithInvalidFieldDefinition(): void
    {
        // Arrange
        $mappedData = ['mapped_value' => 'test'];
        $fieldDefinitions = [
            new \stdClass()
        ];

        // Assert
        $this->expectException(FieldMapperException::class);

        // Act
        $this->structureBuilder->buildNestedArray($mappedData, $fieldDefinitions);
    }

    public function testBuildNestedArrayWithSharedParentPath(): void
    {
        // Arrange
        $mappedData = [
            'mapped_id' => 'ID001',
            'mapped_type' => 'TYPE001'
        ];
        $fieldDefinitions = [
            $this->createFieldDefinition(
                field: 'id',
                mapsTo: 'mapped_id',
                xmlPath: ['Root/Data/Info/ID'],
            ),
            $this->createFieldDefinition(
                field: 'type',
                mapsTo: 'mapped_type',
                xmlPath: ['Root/Data/Info/Type'],
            )
        ];

        // Act
        $result = $this->structureBuilder->buildNestedArray($mappedData, $fieldDefinitions);

        // Assert
        $expected = [
            'Root' => [
                'Data' => [
                    'Info' => [
                        'ID' => [
                            'mapped_id' => 'ID001'
                        ],
                        'Type' => [
                            'mapped_type' => 'TYPE001'
                        ]
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $result);
    }
}