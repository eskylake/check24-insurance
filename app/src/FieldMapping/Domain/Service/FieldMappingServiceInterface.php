<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

interface FieldMappingServiceInterface
{
    public function processFields(array $inputs, array $mappings): array;
}