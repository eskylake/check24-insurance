<?php

declare(strict_types=1);

namespace App\Insurance\Domain\ValueObject;

use App\Insurance\Domain\Exception\FieldDefinitionException;

/**
 * Represents a mapping configuration with a root path and field definitions.
 *
 * @final
 */
final class Mapping
{
    /**
     * The root path for the mapping.
     *
     * @var string
     */
    private string $root;

    /**
     * The collection of field definitions.
     *
     * @var array<string, mixed>
     */
    private array $fieldDefinitions;

    /**
     * Constructs a new Mapping instance.
     *
     * @param string               $root             The root path for the mapping
     * @param array<string, mixed> $fieldDefinitions The collection of field definitions
     */
    public function __construct(
        string $root,
        array  $fieldDefinitions,
    )
    {
        $this->root = $root;
        $this->fieldDefinitions = $fieldDefinitions;
    }

    /**
     * Creates a Mapping instance from an array of mapping configurations.
     *
     * Validates the input array and creates a new Mapping object.
     *
     * @param array<string, mixed> $mappings The mapping configuration array
     * @return self A new Mapping instance
     * @throws FieldDefinitionException If field definitions are missing
     */
    public static function fromArray(array $mappings): self
    {
        self::validate($mappings);

        return new self(
            $mappings['root'],
            $mappings['field_definitions'],
        );
    }

    /**
     * Validates the mapping configuration array.
     *
     * Ensures that field definitions are present in the input array.
     *
     * @param array<string, mixed> $mappings The mapping configuration array
     * @throws FieldDefinitionException If field definitions are missing
     */
    private static function validate(array $mappings): void
    {
        if (!isset($mappings['field_definitions'])) {
            throw new FieldDefinitionException();
        }
    }

    /**
     * Retrieves the root path.
     *
     * @return string The root path for the mapping
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * Retrieves the field definitions.
     *
     * @return array<string, mixed> The collection of field definitions
     */
    public function getFieldDefinitions(): array
    {
        return $this->fieldDefinitions;
    }
}