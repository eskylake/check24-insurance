<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\DataObject\ProcessedField;
use App\FieldMapping\Domain\Exception\{FieldValidationException, FieldMapperException};
use App\FieldMapping\Domain\Service\{
    FieldMapperServiceInterface,
    FieldMappingServiceInterface,
    FieldValidatorServiceInterface
};

/**
 * Service orchestrating the field processing workflow including validation and mapping.
 *
 * @implements FieldMappingServiceInterface
 */
class FieldMappingService implements FieldMappingServiceInterface
{
    /**
     * Constructs a new FieldMappingService instance.
     *
     * @param FieldValidatorServiceInterface $validator Service for validating field definitions
     * @param FieldMapperServiceInterface $mapper Service for mapping field data according to definitions
     */
    public function __construct(
        private FieldValidatorServiceInterface $validator,
        private FieldMapperServiceInterface    $mapper,
    )
    {
    }

    /**
     * Processes input fields through validation and mapping stages.
     *
     * @param array $inputs Raw input data to be processed
     * @param array $fieldDefinitions Definitions describing how fields should be processed
     *
     * @throws FieldValidationException
     * @throws FieldMapperException
     *
     * @return ProcessedField Object containing the validated field definitions and mapped data
     */
    public function processFields(array $inputs, array $fieldDefinitions): ProcessedField
    {
        $fieldDefs = $this->validator->validate($inputs, $fieldDefinitions);
        $mappedData = $this->mapper->map($inputs, $fieldDefs);

        return new ProcessedField($fieldDefs, $mappedData);
    }
}