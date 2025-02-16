<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\DataObject\MappedData;

interface FieldMapperServiceInterface
{
    public function map(array $data, array $fieldDefinitions): MappedData;
}