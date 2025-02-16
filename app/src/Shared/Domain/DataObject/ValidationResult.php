<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataObject;

/**
 * Represents the result of a validation process.
 *
 * @final
 */
final class ValidationResult
{
    /**
     * Indicates whether the validation was successful.
     *
     * @var bool
     */
    private bool $isValid;

    /**
     * Collection of validation errors.
     *
     * @var array<string>
     */
    private array $errors;

    /**
     * Constructs a new ValidationResult instance.
     *
     * @param bool          $isValid Indicates if the validation passed
     * @param array<string> $errors  Collection of validation error messages
     */
    private function __construct(
        bool  $isValid,
        array $errors = [],
    )
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    /**
     * Creates a ValidationResult representing a successful validation.
     *
     * @return self A ValidationResult instance marked as valid
     */
    public static function valid(): self
    {
        return new self(true);
    }

    /**
     * Creates a ValidationResult representing a failed validation.
     *
     * @param array<string> $errors Collection of validation error messages
     * @return self A ValidationResult instance marked as invalid
     */
    public static function invalid(array $errors): self
    {
        return new self(false, $errors);
    }

    /**
     * Checks if the validation was successful.
     *
     * @return bool True if validation passed, false otherwise
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Retrieves the validation errors.
     *
     * @return array<string> Collection of error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}