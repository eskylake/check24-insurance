<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use DateTime;
use App\FieldMapping\Domain\Exception\FieldValidationException;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;
use App\FieldMapping\Domain\Service\FieldValidatorServiceInterface;

class FieldValidatorService implements FieldValidatorServiceInterface
{
    public function __construct(private FieldStaticServiceInterface $fieldStaticService)
    {
    }

    public function validate(array $data, array $fieldDefinitions): array
    {
        $errors = [];
        $fieldDefs = [];

        foreach ($fieldDefinitions as $fieldName => $definition) {
            $fieldDef = new FieldDefinition(
                $definition['field'],
                $definition['maps_to'],
                $definition['required'] ?? false,
                $definition['description'] ?? null,
                $definition['values'] ?? null,
                $definition['validation'] ?? [],
                $definition['static'] ?? null,
            );

            if (!isset($data[$fieldName]) && $fieldDef->isRequired()) {
                $errors[$fieldName][] = "Field is required";
                continue;
            }

            if (!isset($data[$fieldName]) && !$fieldDef->getStatic()) {
                continue;
            }

            $value = $data[$fieldName] ?? $this->fieldStaticService->handleInput($fieldDef);
            $validation = $fieldDef->getValidation();

            if (isset($validation['type'])) {
                switch ($validation['type']) {
                    case 'string':
                        if (!is_string($value)) {
                            $errors[$fieldName][] = "Must be a string";
                        }
                        if (isset($validation['allowed_values']) &&
                            !in_array($value, $validation['allowed_values'])) {
                            $errors[$fieldName][] = "Invalid value";
                        }
                        break;

                    case 'integer':
                        if (!is_numeric($value)) {
                            $errors[$fieldName][] = "Must be a number";
                        }
                        if (isset($validation['min']) && $value < $validation['min']) {
                            $errors[$fieldName][] = "Value must be >= {$validation['min']}";
                        }
                        if (isset($validation['max']) && $value > $validation['max']) {
                            $errors[$fieldName][] = "Value must be <= {$validation['max']}";
                        }
                        break;

                    case 'date':
                        if (!empty($value)) {
                            $format = $validation['format'] ?? 'Y-m-d';
                            $date = $value instanceof DateTime ? $value : DateTime::createFromFormat($format, $value);
                            if (!$date || $date->format($format) !== $value) {
                                $errors[$fieldName][] = "Invalid date format";
                            }
                        }
                        break;
                }
            }

            if ($fieldDef->getValues() !== null) {
                if (!array_key_exists($value, $fieldDef->getValues())) {
                    $errors[$fieldName][] = "Invalid mapping value";
                }
            }

            $fieldDefs[] = $fieldDef;
        }

        if (!empty($errors)) {
            throw new FieldValidationException($errors);
        }

        return $fieldDefs;
    }
}