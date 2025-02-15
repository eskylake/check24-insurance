<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Aggregate;

final class FieldDefinition
{
    private string $field;
    private string $mapsTo;
    private bool $required;
    private ?string $description;
    private ?array $values;
    private array $validation;
    private mixed $static;
    private array $xmlPaths;

    public function __construct(
        string $field,
        string $mapsTo,
        bool $required,
        ?string $description,
        ?array $values,
        array $validation,
        mixed $static,
        array $xmlPaths,
    ) {
        $this->field = $field;
        $this->mapsTo = $mapsTo;
        $this->required = $required;
        $this->description = $description;
        $this->values = $values;
        $this->validation = $validation;
        $this->static = $static;
        $this->xmlPaths = $xmlPaths;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getMapsTo(): string
    {
        return $this->mapsTo;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getValues(): ?array
    {
        return $this->values;
    }

    public function getValidation(): array
    {
        return $this->validation;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getStatic(): mixed
    {
        return $this->static;
    }

    public function getXMLPaths(): array
    {
        return $this->xmlPaths;
    }
}