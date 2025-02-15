<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

class ProcessedField
{
    private array $fieldDefs;

    private array $mappedData;

    public function __construct(
        array $fieldDefs,
        array $mappedData,
    )
    {
        $this->fieldDefs = $fieldDefs;
        $this->mappedData = $mappedData;
    }

    public function getFieldDefs(): array
    {
        return $this->fieldDefs;
    }

    public function getMappedData(): array
    {
        return $this->mappedData;
    }
}