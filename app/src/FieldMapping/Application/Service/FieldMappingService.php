<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\DataObject\ProcessedField;
use App\FieldMapping\Domain\Exception\FieldDefinitionException;
use App\FieldMapping\Domain\Service\{FieldMapperServiceInterface,
    FieldMappingServiceInterface,
    FieldValidatorServiceInterface};

class FieldMappingService implements FieldMappingServiceInterface
{
    public function __construct(
        private FieldValidatorServiceInterface $validator,
        private FieldMapperServiceInterface    $mapper,
    )
    {
    }

    public function processFields(array $inputs, array $mappings): ProcessedField
    {
        $mappingFieldDefs = $mappings['field_definitions'] ?? throw new FieldDefinitionException();
        $fieldDefs = $this->validator->validate($inputs, $mappingFieldDefs);

        $mappedData = $this->mapper->map($inputs, $fieldDefs);

        return new ProcessedField($fieldDefs, $mappedData);
    }
}