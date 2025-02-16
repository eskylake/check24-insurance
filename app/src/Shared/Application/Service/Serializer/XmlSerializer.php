<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Serializer;

use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Domain\Serializer\XmlSerializerInterface;

/**
 * Service for serializing data structures into XML format.
 *
 * This service wraps Symfony's Serializer component to provide specialized XML
 * serialization with configurable root elements and encoding. It handles:
 * - Custom XML root node naming
 * - UTF-8 encoding
 * - Context-based serialization configuration
 * - Structured data conversion to XML
 *
 * @implements XmlSerializerInterface
 *
 * @final
 */
final class XmlSerializer implements XmlSerializerInterface
{
    /**
     * Constructs a new XmlSerializer instance.
     *
     * @param SerializerInterface $serializer Symfony serializer component for data transformation
     */
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    /**
     * Serializes data into XML format with specified root element.
     *
     * @param string $root    Name of the XML root element
     * @param mixed  $data    Data to be serialized into XML
     * @param array  $context Additional context options for serialization
     *
     * @return string Generated XML string with specified root element
     */
    public function serialize(string $root, mixed $data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'xml', [
            ...$context,
            'xml_root_node_name' => $root,
            'xml_encoding' => 'UTF-8',
        ]);
    }
}
