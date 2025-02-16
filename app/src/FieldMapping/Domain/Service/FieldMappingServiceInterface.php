<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\DataObject\ProcessedField;

/**
 * Interface orchestrating the field processing workflow including validation and mapping.
 */
interface FieldMappingServiceInterface
{

    /**
     * Processes input fields through validation and mapping stages.
     *
     * @param array $inputs           Raw input data to be processed
     * @param array $fieldDefinitions Definitions describing how fields should be processed
     *
     * @return ProcessedField Object containing the validated field definitions and mapped data
     */
    public function processFields(array $inputs, array $fieldDefinitions): ProcessedField;
}