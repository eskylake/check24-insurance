<?php

declare(strict_types=1);

namespace App\Shared\Domain\Serializer;

/**
 * Interface for serializing data structures into XML format.
 */
interface XmlSerializerInterface
{
    /**
     * Serializes data into XML format with specified root element.
     *
     * @param string $root    Name of the XML root element
     * @param mixed  $data    Data to be serialized into XML
     * @param array  $context Additional context options for serialization
     *
     * @return string Generated XML string with specified root element
     */
    public function serialize(string $root, mixed $data, array $context = []): string;
}
