<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\DataObject\ProcessedField;
use App\FieldMapping\Domain\Service\{
    FieldMapperServiceInterface,
    FieldMappingServiceInterface,
    FieldValidatorServiceInterface
};

class FieldMappingService implements FieldMappingServiceInterface
{
    public function __construct(
        private FieldValidatorServiceInterface $validator,
        private FieldMapperServiceInterface    $mapper,
    )
    {
    }

    public function processFields(array $inputs, array $fieldDefinitions): ProcessedField
    {
        $fieldDefs = $this->validator->validate($inputs, $fieldDefinitions);
        $mappedData = $this->mapper->map($inputs, $fieldDefs);

        return new ProcessedField($fieldDefs, $mappedData);
    }
}