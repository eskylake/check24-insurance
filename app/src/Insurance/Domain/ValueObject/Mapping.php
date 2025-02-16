<?php

declare(strict_types=1);

namespace App\Insurance\Domain\ValueObject;

use App\Insurance\Domain\Exception\FieldDefinitionException;

final class Mapping
{
    private string $root;

    private array $fieldDefinitions;

    public function __construct(
        string $root,
        array  $fieldDefinitions,
    )
    {
        $this->root = $root;
        $this->fieldDefinitions = $fieldDefinitions;
    }

    public static function fromArray(array $mappings): self
    {
        self::validate($mappings);

        return new self(
            $mappings['root'],
            $mappings['field_definitions'],
        );
    }

    private static function validate(array $mappings): void
    {
        if (!isset($mappings['field_definitions'])) {
            throw new FieldDefinitionException();
        }
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function getFieldDefinitions(): array
    {
        return $this->fieldDefinitions;
    }
}