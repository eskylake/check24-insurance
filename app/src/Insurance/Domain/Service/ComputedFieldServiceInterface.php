<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Service;

/**
 * Interface responsible for computing dynamic field values based on input data.
 */
interface ComputedFieldServiceInterface
{
    /**
     * Computes values for all provided computed fields using their respective computation methods.
     *
     * @param array $computed Array of field definitions for computed fields
     * @param array $inputs   Input data used for computing field values
     *
     * @return array Array of computed values where keys are the mapped field
     *                             names and values are the computed results
     */
    public function compute(array $computed, array $inputs): array;
}