<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Serializer;

use App\Tests\Unit\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Application\Service\Serializer\XmlSerializer;

class XmlSerializerTest extends TestCase
{
    private SerializerInterface $symfonySerializer;

    private XmlSerializer $serializer;

    protected function setUp(): void
    {
        $this->symfonySerializer = $this->createMock(SerializerInterface::class);
        $this->serializer = new XmlSerializer($this->symfonySerializer);
    }

    public function testSerializeWithSimpleData(): void
    {
        // Arrange
        $root = 'Root';
        $data = ['field' => 'value'];
        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?><Root><field>value</field></Root>';

        $this->symfonySerializer
            ->expects($this->once())
            ->method('serialize')
            ->with(
                $data,
                'xml',
                [
                    'xml_root_node_name' => $root,
                    'xml_encoding' => 'UTF-8'
                ]
            )
            ->willReturn($expectedXml);

        // Act
        $result = $this->serializer->serialize($root, $data);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testSerializeWithContext(): void
    {
        // Arrange
        $root = 'Root';
        $data = ['field' => 'value'];
        $context = ['xml_version' => '1.1'];
        $expectedXml = '<?xml version="1.1" encoding="UTF-8"?><Root><field>value</field></Root>';

        $this->symfonySerializer
            ->expects($this->once())
            ->method('serialize')
            ->with(
                $data,
                'xml',
                [
                    'xml_version' => '1.1',
                    'xml_root_node_name' => $root,
                    'xml_encoding' => 'UTF-8'
                ]
            )
            ->willReturn($expectedXml);

        // Act
        $result = $this->serializer->serialize($root, $data, $context);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }
}