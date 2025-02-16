<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;

/**
 * Interface responsible for building nested array structures based on XML path definitions.
 */
interface XmlStructureBuilderServiceInterface
{
    /**
     * Builds a nested array structure based on XML paths in field definitions.
     *
     * @param array<string, mixed>   $mappedData       Associative array of mapped field values where
     *                                                 keys are the mapped field names and values are
     *                                                 the corresponding data
     * @param array<FieldDefinition> $fieldDefinitions Array of field definitions containing XML path
     *                                                 and mapping information
     *
     * @return array<string, mixed> Nested array structure representing the XML hierarchy
     *                             with mapped values placed at appropriate positions
     */
    public function buildNestedArray(array $mappedData, array $fieldDefinitions): array;
}