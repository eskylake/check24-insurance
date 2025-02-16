<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

final class StringValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $constraints = []): ValidationResult
    {
        if (!is_string($value)) {
            return ValidationResult::invalid(['Must be a string']);
        }

        if (isset($constraints['allowed_values']) && !in_array($value, $constraints['allowed_values'])) {
            return ValidationResult::invalid(['Not allowed value']);
        }

        return ValidationResult::valid();
    }
}