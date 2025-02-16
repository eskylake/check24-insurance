<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Exception;

use DomainException;

/**
 * Exception thrown when field validation fails with detailed error information.
 *
 * @extends DomainException Represents a domain-specific error in field validation
 */
class FieldValidationException extends DomainException
{
    /**
     * @var array<string, array<string>> Map of field names to their validation error messages
     */
    private array $errors;

    /**
     * Constructs a new FieldValidationException with validation errors.
     *
     * @param array<string, array<string>> $errors Map of field names to their validation
     *                                             error messages
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Field validation failed');
    }

    /**
     * @return array<string, array<string>> Map of field names to their validation
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}