<?php

declare(strict_types=1);

namespace App\Shared\Domain\Validator;

use App\Shared\Domain\DataObject\ValidationResult;

/**
 * Validator blueprint.
 */
interface ValidatorInterface
{
    /**
     * Validates a value.
     *
     * @param mixed $value       The value to validate
     * @param array $constraints Optional validation constraints.
     *
     * @return ValidationResult Result object indicating validation success or failure
     *                         with appropriate error messages
     */
    public function validate(mixed $value, array $constraints = []): ValidationResult;
}
