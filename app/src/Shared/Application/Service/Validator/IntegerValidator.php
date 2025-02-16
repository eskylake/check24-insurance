<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

/**
 * Validator for numeric values with optional range constraints.
 *
 * This validator performs multiple levels of validation:
 * 1. Type validation to ensure the value is numeric
 * 2. Optional minimum value validation
 * 3. Optional maximum value validation
 *
 * It can be used to validate both simple numeric presence and
 * specific range restrictions.
 *
 * @implements ValidatorInterface
 *
 */
class IntegerValidator implements ValidatorInterface
{
    /**
     * Validates a value as numeric with optional range constraints.
     *
     * First checks if the value is numeric, then optionally validates
     * against minimum and maximum values if provided in the constraints.
     *
     * @param mixed $value       The value to validate
     * @param array $constraints Optional validation constraints. Supported keys:
     *                           - 'min': minimum allowed value
     *                           - 'max': maximum allowed value
     *
     * @return ValidationResult Result object indicating validation success or failure
     *                         with appropriate error messages
     *
     * @example
     * ```php
     * $validator = new IntegerValidator();
     *
     * // Simple numeric validation
     * $result = $validator->validate(42);
     * // Returns: ValidationResult::valid()
     *
     * // Non-numeric validation
     * $result = $validator->validate('abc');
     * // Returns: ValidationResult::invalid(['Must be a number'])
     *
     * // Range validation
     * $result = $validator->validate(5, ['min' => 1, 'max' => 10]);
     * // Returns: ValidationResult::valid()
     *
     * // Below minimum
     * $result = $validator->validate(0, ['min' => 1]);
     * // Returns: ValidationResult::invalid(['Value must be >= 1'])
     *
     * // Above maximum
     * $result = $validator->validate(11, ['max' => 10]);
     * // Returns: ValidationResult::invalid(['Value must be <= 10'])
     * ```
     */
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