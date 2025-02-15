<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

interface FieldMapperServiceInterface
{
    public function map(array $data, array $fieldDefinitions): array;
}