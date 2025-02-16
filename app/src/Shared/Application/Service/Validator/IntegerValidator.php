<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

final class IntegerValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $constraints = []): ValidationResult
    {
        if (!is_numeric($value)) {
            return ValidationResult::invalid(['Must be a number']);
        }

        if (isset($constraints['min']) && $value < $constraints['min']) {
            return ValidationResult::invalid([sprintf('Value must be >= %s', $constraints['min'])]);
        }

        if (isset($constraints['max']) && $value > $constraints['max']) {
            return ValidationResult::invalid([sprintf('Value must be <= %s', $constraints['max'])]);
        }

        return ValidationResult::valid();
    }
}