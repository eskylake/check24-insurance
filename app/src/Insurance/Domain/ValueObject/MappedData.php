<?php

declare(strict_types=1);

namespace App\Insurance\Domain\ValueObject;

/**
 * Represents a collection of mapped and computed data.
 *
 * @final
 */
final class MappedData
{
    /**
     * The array of mapped data.
     *
     * @var array<string, mixed>
     */
    private array $mapped;

    /**
     * The array of computed data.
     *
     * @var array<string, mixed>
     */
    private array $computed;

    /**
     * Constructs a new MappedData instance.
     *
     * @param array<string, mixed> $mapped   The mapped data array
     * @param array<string, mixed> $computed The computed data array
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
     * Retrieves the mapped data.
     *
     * @return array<string, mixed> The mapped data array
     */
    public function getMapped(): array
    {
        return $this->mapped;
    }

    /**
     * Retrieves the computed data.
     *
     * @return array<string, mixed> The computed data array
     */
    public function getComputed(): array
    {
        return $this->computed;
    }

    /**
     * Combines mapped and computed data into a single array.
     *
     * Merges the mapped and computed data arrays using the spread operator,
     * with computed data appended after mapped data.
     *
     * @return array<string, mixed> A combined array of mapped and computed data
     */
    public function toArray(): array
    {
        return [...$this->getMapped(), ...$this->getComputed()];
    }
}