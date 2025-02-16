<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

final class ValidationRule
{
    public function __construct(
        private string $type,
        private array  $constraints = [],
    )
    {
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