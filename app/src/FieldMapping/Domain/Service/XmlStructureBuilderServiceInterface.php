<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

interface XmlStructureBuilderServiceInterface
{
    public function buildNestedArray(array $mappedData, array $fieldDefinitions): array;
}