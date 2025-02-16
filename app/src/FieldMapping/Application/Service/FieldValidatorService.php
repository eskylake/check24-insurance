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

class FieldValidatorService implements FieldValidatorServiceInterface
{
    public function __construct(
        private FieldStaticServiceInterface $fieldStaticService,
        private ValidatorFactory            $validatorFactory,
    )
    {
    }

    /**
     * @param array $data
     * @param array $fieldDefinitions
     * @return array<FieldDefinition>
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