<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Aggregate;

use App\FieldMapping\Domain\ValueObject\XmlPath;

final class FieldDefinition
{
    private string $field;

    private string $mapsTo;

    private bool $required;

    private ?string $description;

    private ?array $values;

    private array $validation;

    private mixed $static;

    private XmlPath $xmlPath;

    public function __construct(
        string $field,
        string $mapsTo,
        bool $required,
        ?string $description,
        ?array $values,
        array $validation,
        mixed $static,
        XmlPath $xmlPath,
    ) {
        $this->field = $field;
        $this->mapsTo = $mapsTo;
        $this->required = $required;
        $this->description = $description;
        $this->values = $values;
        $this->validation = $validation;
        $this->static = $static;
        $this->xmlPath = $xmlPath;
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

    public function getXMLPath(): XmlPath
    {
        return $this->xmlPath;
    }
}