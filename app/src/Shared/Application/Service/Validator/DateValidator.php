<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use DateTime;
use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

/**
 * Validator for date values with format constraints.
 *
 * This validator handles date validation by:
 * - Supporting both DateTime objects and string inputs
 * - Validating against specific date formats
 * - Ensuring strict format matching
 *
 * The validator uses PHP's DateTime for parsing and can be configured
 * with different date formats through constraints.
 *
 * @implements ValidatorInterface
 *
 */
class DateValidator implements ValidatorInterface
{
    /**
     * Validates a date value against a specified format.
     *
     * Accepts either a DateTime object or a string representation of a date
     * and validates it against the provided format (defaults to 'Y-m-d').
     * Ensures strict format matching to prevent partial date matches.
     *
     * @param mixed $value                 The value to validate (DateTime object or string)
     * @param array $constraints           Optional validation constraints. Supported keys:
     *                                     - 'format': string format following PHP's date format syntax
     *                                     (defaults to 'Y-m-d')
     *
     * @return ValidationResult Result object indicating validation success or failure
     *                         with appropriate error messages
     *
     * @example
     * ```php
     * $validator = new DateValidator();
     *
     * // DateTime object validation
     * $result = $validator->validate(new DateTime());
     * // Returns: ValidationResult::valid()
     *
     * // String date validation with default format
     * $result = $validator->validate('2025-02-17');
     * // Returns: ValidationResult::valid()
     *
     * // Custom format validation
     * $result = $validator->validate('17/02/2025', ['format' => 'd/m/Y']);
     * // Returns: ValidationResult::valid()
     *
     * // Invalid date
     * $result = $validator->validate('invalid-date');
     * // Returns: ValidationResult::invalid(['Invalid date format'])
     * ```
     */
    public function validate(mixed $value, array $constraints = []): ValidationResult
    {
        $format = $constraints['format'] ?? 'Y-m-d';
        $date = $value instanceof DateTime ? $value : DateTime::createFromFormat($format, $value);

        if (!$date || $date->format($format) !== $value) {
            return ValidationResult::invalid(['Invalid date format']);
        }

        return ValidationResult::valid();
    }
}