<?php

declare(strict_types=1);

namespace App\Insurance\Application\Service;

use RuntimeException;
use App\Insurance\Domain\ValueObject\Mapping;
use App\Shared\Domain\Serializer\XmlSerializerInterface;
use App\Insurance\Domain\Service\RequestBuilderServiceInterface;

/**
 * Service responsible for building XML requests from structured data.
 *
 * @implements RequestBuilderServiceInterface
 *
 * @final
 */
final class RequestBuilderService implements RequestBuilderServiceInterface
{
    /**
     * Constructs a new RequestBuilderService instance.
     *
     * @param XmlSerializerInterface $serializer Service for converting structures to XML
     */
    public function __construct(
        private XmlSerializerInterface $serializer,
    )
    {
    }

    /**
     * Builds an XML request string from a mapping configuration and data structure.
     *
     * Takes a mapping configuration (which defines the XML structure and root element)
     * and a data structure, then generates a properly formatted XML request string.
     * The root element is extracted from the mapping and used as the XML document root.
     *
     * @param Mapping              $mapping   Configuration containing XML structure information
     *                                        and root element definition
     * @param array<string, mixed> $structure Hierarchical data structure to be converted
     *                                        to XML
     *
     * @return string Generated XML request string
     *
     * @throws RuntimeException If serialization fails
     *
     * @example
     * ```php
     * $mapping = new Mapping(['root' => 'Request']);
     * $structure = [
     *     'Data' => [
     *         'Field' => 'value'
     *     ]
     * ];
     *
     * $result = $service->buildRequest($mapping, $structure);
     * // Returns: <?xml version="1.0"?><Request><Data><Field>value</Field></Data></Request>
     * ```
     */
    public function buildRequest(Mapping $mapping, array $structure): string
    {
        return $this->serializer->serialize($mapping->getRoot(), $structure);
    }
}