<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

final class FieldDefinition
{
    private string $field;

    private string $mapsTo;

    private bool $required;

    private array $validation;

    private mixed $static;

    private bool $computed;

    private XmlPath $xmlPath;

    private ?string $description;

    private ?array $values;

    public function __construct(
        string $field,
        string $mapsTo,
        bool $required,
        array $validation,
        mixed $static,
        bool $computed,
        XmlPath $xmlPath,
        ?array $values,
        ?string $description,
    ) {
        $this->field = $field;
        $this->mapsTo = $mapsTo;
        $this->required = $required;
        $this->validation = $validation;
        $this->static = $static;
        $this->computed = $computed;
        $this->xmlPath = $xmlPath;
        $this->values = $values;
        $this->description = $description;
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

    public function isComputed(): bool
    {
        return $this->computed;
    }

    public function getXMLPath(): XmlPath
    {
        return $this->xmlPath;
    }
}