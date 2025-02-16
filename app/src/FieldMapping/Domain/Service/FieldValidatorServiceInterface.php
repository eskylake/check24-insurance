<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

/**
 * Interface responsible for validating field definitions and their corresponding data.
 */
interface FieldValidatorServiceInterface
{

    /**
     * Validates field data against their definitions and returns valid field definitions.
     *
     * @param array<string, mixed> $data             Input data to validate, where keys are field names
     *                                               and values are the corresponding field values
     * @param array<string, array> $fieldDefinitions Array of field definition configurations where keys
     */
    public function validate(array $data, array $fieldDefinitions): array;
}