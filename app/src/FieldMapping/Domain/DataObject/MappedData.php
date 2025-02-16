<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\DataObject;

/**
 * Data object containing mapped fields and computed values.
 *
 * This object holds two types of field data:
 * 1. Mapped fields: Direct field mappings from source to target
 * 2. Computed fields: Fields that require calculation or transformation
 *
 * The data object provides immutable access to both types of field data
 * and maintains separation between mapped and computed values.
 *
 * @final
 */
final class MappedData
{
    /**
     * @var array<string, mixed> Map of field names to their mapped values
     */
    private array $mapped;

    /**
     * @var array<string, mixed> Map of field names to their computed values
     */
    private array $computed;

    /**
     * Constructs a new MappedData instance.
     *
     * @param array<string, mixed> $mapped   Array of directly mapped field values
     * @param array<string, mixed> $computed Array of computed field values
     */
    public function __construct(
        array $mapped,
        array $computed,
    )
    {
        $this->mapped = $mapped;
        $this->computed = $computed;
    }

    /**
     * @return array<string, mixed> Array of mapped field values where keys are
     *                             field names and values are the mapped data
     */
    public function getMapped(): array
    {
        return $this->mapped;
    }

    /**
     * @return array<string, mixed> Array of computed field values where keys are
     *                             field names and values are the computed data
     */
    public function getComputed(): array
    {
        return $this->computed;
    }
}