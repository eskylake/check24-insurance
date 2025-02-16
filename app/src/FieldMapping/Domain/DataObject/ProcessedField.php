<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\DataObject;

/**
 * Data object containing processed field definitions and their mapped values.
 *
 * This object holds the result of field processing operations, containing:
 * 1. Field definitions: Validated and processed field configuration
 * 2. Mapped data: Resulting mapped and computed values
 *
 * The object serves as a container for both the field structure (definitions)
 * and their processed values, maintaining the relationship between
 * field definitions and their corresponding data.
 *
 * @final
 */
final class ProcessedField
{
    /**
     * @var array Array of field definition objects describing
     *                                  the structure and rules for each field
     */
    private array $fieldDefs;

    /**
     * @var MappedData Object containing both mapped values and computed results
     *                 for the processed fields
     */
    private MappedData $mappedData;

    /**
     * Constructs a new ProcessedField instance.
     *
     * @param array      $fieldDefs                   Array of field definitions
     *                                                resulting from processing
     * @param MappedData $mappedData                  Object containing the mapped
     *                                                and computed values
     */
    public function __construct(
        array      $fieldDefs,
        MappedData $mappedData,
    )
    {
        $this->fieldDefs = $fieldDefs;
        $this->mappedData = $mappedData;
    }

    /**
     * @return array Array of field definition objects describing the structure and rules for each field
     */
    public function getFieldDefs(): array
    {
        return $this->fieldDefs;
    }

    /**
     * @return MappedData Object containing both mapped values and computed results
     */
    public function getMappedData(): MappedData
    {
        return $this->mappedData;
    }
}