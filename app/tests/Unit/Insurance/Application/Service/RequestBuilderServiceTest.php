<?php

declare(strict_types=1);

namespace App\Tests\Unit\Insurance\Application\Service;

use RuntimeException;
use App\Tests\Unit\TestCase;
use App\Insurance\Application\Service\RequestBuilderService;
use App\Insurance\Domain\ValueObject\Mapping;
use App\Shared\Domain\Serializer\XmlSerializerInterface;

class RequestBuilderServiceTest extends TestCase
{
    private XmlSerializerInterface $serializer;

    private RequestBuilderService $requestBuilder;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(XmlSerializerInterface::class);
        $this->requestBuilder = new RequestBuilderService($this->serializer);
    }

    public function testBuildRequestSuccessfully(): void
    {
        // Arrange
        $mapping = new Mapping(
            root: 'DataRequest',
            fieldDefinitions: [
                'field1' => [
                    'field' => 'field1',
                    'maps_to' => 'mapped_field1',
                ],
            ],
        );

        $structure = [
            'Field1' => 'value1',
            'Field2' => 'value2',
        ];
        $expectedXml = '<?xml version="1.0"?><DataRequest><Field1>value1</Field1><Field2>value2</Field2></DataRequest>';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('DataRequest', $structure)
            ->willReturn($expectedXml);

        // Act
        $result = $this->requestBuilder->buildRequest($mapping, $structure);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testBuildRequestWithNestedStructure(): void
    {
        // Arrange
        $mapping = Mapping::fromArray([
            'root' => 'Request',
            'field_definitions' => [
                'data' => [
                    'field' => 'data',
                    'maps_to' => 'mapped_data',
                ],
            ],
        ]);

        $structure = [
            'Data' => [
                'SubData' => [
                    'Field' => 'value',
                ],
            ],
        ];
        $expectedXml = '<?xml version="1.0"?><Request><Data><SubData><Field>value</Field></SubData></Data></Request>';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('Request', $structure)
            ->willReturn($expectedXml);

        // Act
        $result = $this->requestBuilder->buildRequest($mapping, $structure);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testBuildRequestWithEmptyStructure(): void
    {
        // Arrange
        $mapping = new Mapping(
            root: 'EmptyRequest',
            fieldDefinitions: [],
        );

        $structure = [];
        $expectedXml = '<?xml version="1.0"?><EmptyRequest/>';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('EmptyRequest', [])
            ->willReturn($expectedXml);

        // Act
        $result = $this->requestBuilder->buildRequest($mapping, $structure);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testBuildRequestWithSerializationFailure(): void
    {
        // Arrange
        $mapping = new Mapping(
            root: 'Request',
            fieldDefinitions: [
                'invalid' => [
                    'field' => 'invalid',
                    'maps_to' => 'mapped_invalid',
                ],
            ],
        );

        $structure = ['Invalid' => "\x00"];

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('Request', $structure)
            ->willThrowException(new RuntimeException('Invalid XML character in structure'));

        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid XML character in structure');

        // Act
        $this->requestBuilder->buildRequest($mapping, $structure);
    }

    public function testBuildRequestWithSpecialCharacters(): void
    {
        // Arrange
        $mapping = Mapping::fromArray([
            'root' => 'Request',
            'field_definitions' => [
                'special' => [
                    'field' => 'special',
                    'maps_to' => 'mapped_special',
                ],
            ],
        ]);

        $structure = [
            'SpecialField' => 'value & special < characters >',
        ];
        $expectedXml = '<?xml version="1.0"?><Request><SpecialField>value &amp; special &lt; characters &gt;</SpecialField></Request>';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('Request', $structure)
            ->willReturn($expectedXml);

        // Act
        $result = $this->requestBuilder->buildRequest($mapping, $structure);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }
}