<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\DataObject\MappedData;

/**
 * Interface responsible for mapping field data according to provided field definitions.
 */
interface FieldMapperServiceInterface
{

    /**
     * Maps input data according to provided field definitions.
     *
     * This method processes each field definition and:
     * - Separates computed fields for later processing
     * - Applies static values when original data is missing
     * - Transforms values according to defined value mappings
     * - Skips fields that are neither present in input data nor have static values
     *
     * @param array $data             Array of input data to be mapped
     * @param array $fieldDefinitions Array of field definitions that describe how to map the data
     *
     * @return MappedData Data object containing mapped values and computed field definitions
     */
    public function map(array $data, array $fieldDefinitions): MappedData;
}