<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use DateTime;
use App\Shared\Domain\DataObject\ValidationResult;
use App\Shared\Domain\Validator\ValidatorInterface;

final class DateValidator implements ValidatorInterface
{
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