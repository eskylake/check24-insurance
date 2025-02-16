<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

/**
 * Value object representing a field's mapping definition and validation rules.
 *
 * This immutable object encapsulates all the information needed to:
 * - Map a source field to a target field
 * - Apply validation rules
 * - Handle computed or static values
 * - Define XML structure
 *
 * @final
 */
final class FieldDefinition
{
    /**
     * Source field name.
     *
     * @var string
     */
    private string $field;

    /**
     * Target field name in the mapped structure.
     *
     * @var string
     */
    private string $mapsTo;

    /**
     * Whether the field is required.
     *
     * @var bool
     */
    private bool $required;

    /**
     * Validation rules for the field.
     *
     * @var array<string, mixed> Map of validation rules and their parameters
     */
    private array $validation;

    /**
     * Static value for the field, if any.
     *
     * @var mixed
     */
    private mixed $static;

    /**
     * Whether the field's value is computed.
     *
     * @var bool
     */
    private bool $computed;

    /**
     * XML path definition for the field.
     *
     * @var XmlPath
     */
    private XmlPath $xmlPath;

    /**
     * Optional field description.
     *
     * @var string|null
     */
    private ?string $description;

    /**
     * Optional value mappings.
     *
     * @var array<string, string>|null Map of source values to target values
     */
    private ?array $values;

    /**
     * Constructs a new FieldDefinition instance.
     *
     * @param string      $field       Source field name
     * @param string      $mapsTo      Target field name
     * @param bool        $required    Whether the field is required
     * @param array       $validation  Validation rules and parameters
     * @param mixed       $static      Static value for the field
     * @param bool        $computed    Whether the field is computed
     * @param XmlPath     $xmlPath     XML path configuration
     * @param array|null  $values      Optional value mappings
     * @param string|null $description Optional field description
     */
    public function __construct(
        string  $field,
        string  $mapsTo,
        bool    $required,
        array   $validation,
        mixed   $static,
        bool    $computed,
        XmlPath $xmlPath,
        ?array  $values,
        ?string $description,
    )
    {
        $this->field = $field;
        $this->mapsTo = $mapsTo;
        $this->required = $required;
        $this->validation = $validation;
        $this->static = $static;
        $this->computed = $computed;
        $this->xmlPath = $xmlPath;
        $this->values = $values;
        $this->description = $description;
    }

    /**
     * Gets the source field name.
     *
     * @return string Original field name in the source data
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Gets the target field name.
     *
     * @return string Field name in the mapped structure
     */
    public function getMapsTo(): string
    {
        return $this->mapsTo;
    }

    /**
     * Gets the field description.
     *
     * @return string|null Optional description of the field's purpose
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Gets the value mappings.
     *
     * @return array<string, string>|null Map of source values to their target values
     */
    public function getValues(): ?array
    {
        return $this->values;
    }

    /**
     * Gets the validation rules.
     *
     * @return array<string, mixed> Map of validation rules and their parameters
     */
    public function getValidation(): array
    {
        return $this->validation;
    }

    /**
     * Checks if the field is required.
     *
     * @return bool True if the field must be present, false otherwise
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Gets the static value.
     *
     * @return mixed Static value for the field if defined, null otherwise
     */
    public function getStatic(): mixed
    {
        return $this->static;
    }

    /**
     * Checks if the field is computed.
     *
     * @return bool True if the field's value is computed, false otherwise
     */
    public function isComputed(): bool
    {
        return $this->computed;
    }

    /**
     * Gets the XML path configuration.
     *
     * @return XmlPath XML path definition for the field
     */
    public function getXMLPath(): XmlPath
    {
        return $this->xmlPath;
    }
}