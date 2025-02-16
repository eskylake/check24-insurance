<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataObject;

final class ValidationResult
{
    private bool $isValid;

    private array $errors;

    private function __construct(
        bool  $isValid,
        array $errors = [],
    )
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    public static function valid(): self
    {
        return new self(true);
    }

    public static function invalid(array $errors): self
    {
        return new self(false, $errors);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
