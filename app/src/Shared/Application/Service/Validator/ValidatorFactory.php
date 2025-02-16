<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Validator;

use InvalidArgumentException;
use App\Shared\Domain\Validator\ValidatorInterface;

/**
 * Factory for creating and managing data validators.
 *
 * This factory maintains a registry of validators for different data types
 * and provides access to them. It supports:
 * - String validation
 * - Integer validation
 * - Date validation
 *
 * The factory ensures that appropriate validators are available for each
 * supported data type and provides a consistent interface for accessing them.
 *
 */
class ValidatorFactory
{
    /**
     * @var array<string, ValidatorInterface> Map of type names to their validators
     */
    private array $validators;

    /**
     * Constructs a new ValidatorFactory instance.
     *
     * Initializes the validator registry with supported validators:
     * - 'string' validator for text validation
     * - 'integer' validator for number validation
     * - 'date' validator for date/time validation
     *
     * @param StringValidator  $stringValidator  Validator for string values
     * @param IntegerValidator $integerValidator Validator for integer values
     * @param DateValidator    $dateValidator    Validator for date values
     */
    public function __construct(
        private StringValidator  $stringValidator,
        private IntegerValidator $integerValidator,
        private DateValidator    $dateValidator,
    )
    {
        $this->validators = [
            'string' => $this->stringValidator,
            'integer' => $this->integerValidator,
            'date' => $this->dateValidator,
        ];
    }

    /**
     * Retrieves a validator for the specified data type.
     *
     * Returns the appropriate validator for the given type from the registry.
     * Throws an exception if no validator is registered for the specified type.
     *
     * @param string $type The type of validator to retrieve (e.g., 'string', 'integer', 'date')
     *
     * @return ValidatorInterface The validator instance for the specified type
     *
     * @throws InvalidArgumentException When no validator is found for the specified type
     *
     * @example
     * ```php
     * $factory = new ValidatorFactory($stringValidator, $integerValidator, $dateValidator);
     *
     * // Get string validator
     * $validator = $factory->getValidator('string');
     *
     * // Get integer validator
     * $validator = $factory->getValidator('integer');
     *
     * // Invalid type
     * $validator = $factory->getValidator('unknown');
     * // Throws: InvalidArgumentException
     * ```
     */
    public function getValidator(string $type): ValidatorInterface
    {
        if (!isset($this->validators[$type])) {
            throw new InvalidArgumentException(sprintf("No validator found for type: %s", $type));
        }

        return $this->validators[$type];
    }
}
