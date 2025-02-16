<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Service;

use App\Insurance\Domain\ValueObject\Mapping;

/**
 * Interface responsible for building XML requests from structured data.
 */
interface RequestBuilderServiceInterface
{
    /**
     * Builds an XML request string from a mapping configuration and data structure.
     *
     * @param Mapping              $mapping   Configuration containing XML structure information
     *                                        and root element definition
     * @param array<string, mixed> $structure Hierarchical data structure to be converted
     *                                        to XML
     *
     * @return string Generated XML request string
     */
    public function buildRequest(Mapping $mapping, array $structure): string;
}