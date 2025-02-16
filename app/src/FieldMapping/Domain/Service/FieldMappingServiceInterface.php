<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\DataObject\ProcessedField;

interface FieldMappingServiceInterface
{
    public function processFields(array $inputs, array $mappings): ProcessedField;
}