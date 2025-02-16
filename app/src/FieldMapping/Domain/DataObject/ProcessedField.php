<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\DataObject;

final class ProcessedField
{
    private array $fieldDefs;

    private MappedData $mappedData;

    public function __construct(
        array $fieldDefs,
        MappedData $mappedData,
    )
    {
        $this->fieldDefs = $fieldDefs;
        $this->mappedData = $mappedData;
    }

    public function getFieldDefs(): array
    {
        return $this->fieldDefs;
    }

    public function getMappedData(): MappedData
    {
        return $this->mappedData;
    }
}