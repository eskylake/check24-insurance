<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\ValueObject\XmlPath;
use App\Shared\Domain\DataObject\ValidationResult;
use App\FieldMapping\Domain\ValueObject\ValidationRule;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\Shared\Infrastructure\Validator\ValidatorFactory;
use App\FieldMapping\Domain\Exception\FieldValidationException;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;
use App\FieldMapping\Domain\Service\FieldValidatorServiceInterface;

/**
 * Service responsible for validating field definitions and their corresponding data.
 *
 * This service performs validation of field data against their definitions, including:
 * - Required field validation
 * - Type-based validation using validation rules
 * - Value mapping validation
 * - Static value handling
 * - Computed field validation
 *
 * @implements FieldValidatorServiceInterface
 */
class FieldValidatorService implements FieldValidatorServiceInterface
{
    /**
     * Constructs a new FieldValidatorService instance.
     *
     * @param FieldStaticServiceInterface $fieldStaticService Service for handling static field values
     * @param ValidatorFactory            $validatorFactory    Factory for creating field validators
     */
    public function __construct(
        private FieldStaticServiceInterface $fieldStaticService,
        private ValidatorFactory            $validatorFactory,
    )
    {
    }

    /**
     * Validates field data against their definitions and returns valid field definitions.
     *
     *
     * @param array<string, mixed> $data             Input data to validate, where keys are field names
     *                                               and values are the corresponding field values
     * @param array<string, array> $fieldDefinitions Array of field definition configurations where keys
     *                                               are field names and values are definition arrays
     *
     * @throws FieldValidationException When validation errors occur, containing an array of
     *                                 error messages keyed by field name
     *
     * @return array<FieldDefinition> Array of validated field definitions
     *
     * @example
     * Input:
     *   $data = ['code' => 'ABC']
     *   $fieldDefinitions = [
     *     'code' => [
     *       'field' => 'code',
     *       'maps_to' => 'system_code',
     *       'required' => true,
     *       'validation' => ['type' => 'string', 'constraints' => ['length' => 3]],
     *       'xml_path' => ['path' => 'Data/Code']
     *     ]
     *   ]
     */
    public function validate(array $data, array $fieldDefinitions): array
    {
        $errors = [];
        $fieldDefs = [];

        foreach ($fieldDefinitions as $fieldName => $definition) {
            if (!isset($definition['xml_path'])) {
                continue;
            }

            $fieldDef = $this->createFieldDefinition($definition);
            $validationResult = $this->validateField($fieldName, $data, $fieldDef);

            if (!$validationResult->isValid()) {
                $errors[$fieldName] = $validationResult->getErrors();
                continue;
            }

            $fieldDefs[] = $fieldDef;
        }

        if (!empty($errors)) {
            throw new FieldValidationException($errors);
        }

        return $fieldDefs;
    }

    private function createFieldDefinition(array $definition): FieldDefinition
    {
        return new FieldDefinition(
            field: $definition['field'],
            mapsTo: $definition['maps_to'],
            required: $definition['required'] ?? false,
            validation: $definition['validation'] ?? [],
            static: $definition['static'] ?? null,
            computed: $definition['computed'] ?? false,
            xmlPath: XmlPath::fromArray($definition['xml_path']),
            values: $definition['values'] ?? null,
            description: $definition['description'] ?? null,
        );
    }

    private function validateField(string $fieldName, array $data, FieldDefinition $fieldDef): ValidationResult
    {
        if (!isset($data[$fieldName]) && $fieldDef->isRequired()) {
            return ValidationResult::invalid(['Field is required']);
        }

        if (!isset($data[$fieldName]) && !$fieldDef->getStatic() && !$fieldDef->isComputed()) {
            return ValidationResult::valid();
        }

        $value = $fieldDef->isComputed() ?
            $data[$fieldDef->getField()] :
            $data[$fieldName] ?? $this->fieldStaticService->handleInput($fieldDef);
        $validation = $fieldDef->getValidation();

        if (isset($validation['type'])) {
            $validationRule = ValidationRule::fromArray($validation);
            $validator = $this->validatorFactory->getValidator($validationRule->getType());
            $result = $validator->validate($value, $validationRule->getConstraints());

            if (!$result->isValid()) {
                return $result;
            }
        }

        if ($fieldDef->getValues() !== null && !$fieldDef->isComputed()) {
            if (!array_key_exists($value, $fieldDef->getValues())) {
                return ValidationResult::invalid([sprintf('Invalid mapping value for [%s]', $fieldName)]);
            }
        }

        return ValidationResult::valid();
    }
}