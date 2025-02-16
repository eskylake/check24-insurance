<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

final class ValidationRule
{
    private string $type;

    private array $constraints;

    public function __construct(
        string $type,
        array  $constraints = [],
    )
    {
        $this->type = $type;
        $this->constraints = $constraints;
    }

    public static function fromArray(array $validation): self
    {
        return new self(
            $validation['type'],
            array_diff_key($validation, ['type' => true]),
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }
}