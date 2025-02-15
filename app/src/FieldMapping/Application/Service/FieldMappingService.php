<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\Service\{
    FieldValidatorServiceInterface,
    FieldMapperServiceInterface,
    FieldMappingServiceInterface
};

class FieldMappingService implements FieldMappingServiceInterface
{
    public function __construct(
        private FieldValidatorServiceInterface $validator,
        private FieldMapperServiceInterface    $mapper,
    )
    {
    }

    public function processFields(array $inputs, array $mappings): array
    {
        $mappingFieldDefs = $mappings['field_definitions'];
        $fieldDefs = $this->validator->validate($inputs, $mappingFieldDefs);

        return $this->mapper->map($inputs, $fieldDefs);
    }
}