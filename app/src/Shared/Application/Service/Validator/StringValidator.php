<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

/**
 * Validator for string values with optional allowed value constraints.
 *
 * This validator performs two levels of validation:
 * 1. Type validation to ensure the value is a string
 * 2. Optional allowed values validation when constraints are provided
 *
 * It can be used to validate both simple string presence and
 * specific string value restrictions.
 *
 * @implements ValidatorInterface
 */
class StringValidator implements ValidatorInterface
{
    /**
     * Validates a value as a string with optional allowed value constraints.
     *
     * First checks if the value is a string, then optionally validates
     * against a list of allowed values if provided in the constraints.
     *
     * @param mixed $value       The value to validate
     * @param array $constraints Optional validation constraints. Supported keys:
     *                           - 'allowed_values': array of valid string values
     *
     * @return ValidationResult Result object indicating validation success or failure
     *                         with appropriate error messages
     *
     * @example
     * ```php
     * $validator = new StringValidator();
     *
     * // Simple string validation
     * $result = $validator->validate('test');
     * // Returns: ValidationResult::valid()
     *
     * // Non-string validation
     * $result = $validator->validate(123);
     * // Returns: ValidationResult::invalid(['Must be a string'])
     *
     * // Allowed values validation
     * $result = $validator->validate('test', ['allowed_values' => ['test', 'demo']]);
     * // Returns: ValidationResult::valid()
     *
     * // Invalid allowed value
     * $result = $validator->validate('invalid', ['allowed_values' => ['test', 'demo']]);
     * // Returns: ValidationResult::invalid(['Not allowed value'])
     * ```
     */
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