<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

/**
 * Represents a validation rule with its type and constraints.
 *
 * @final
 */
final class ValidationRule
{
    /**
     * The type of the validation rule.
     *
     * @var string
     */
    private string $type;

    /**
     * The constraints associated with the validation rule.
     *
     * @var array
     */
    private array $constraints;

    /**
     * Creates a new ValidationRule instance.
     *
     * @param string $type        The type of the validation rule.
     * @param array  $constraints The constraints associated with the validation rule.
     */
    public function __construct(
        string $type,
        array  $constraints = [],
    )
    {
        $this->type = $type;
        $this->constraints = $constraints;
    }

    /**
     * Creates a ValidationRule instance from an array representation.
     *
     * @param array $validation The validation rule array.
     *
     * @return self
     */
    public static function fromArray(array $validation): self
    {
        return new self(
            $validation['type'],
            array_diff_key($validation, ['type' => true]),
        );
    }

    /**
     * Gets the type of the validation rule.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the constraints associated with the validation rule.
     *
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}