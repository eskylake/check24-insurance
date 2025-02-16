<?php

declare(strict_types=1);

namespace App\Insurance\Application\Service;

use BadMethodCallException;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\Insurance\Domain\Service\ComputedFieldServiceInterface;

/**
 * Service responsible for computing dynamic field values based on input data.
 *
 * This service handles the computation of fields whose values are derived from other input fields
 * or require specific transformations. Each computed field has its own computation method that
 * follows the naming convention 'compute{FieldName}'.
 *
 * @implements ComputedFieldServiceInterface
 *
 * @final
 */
final class ComputedFieldService implements ComputedFieldServiceInterface
{
    /**
     * Computes the value for an occasional driver field.
     *
     * @param FieldDefinition      $definition Field definition containing computation rules
     * @param array<string, mixed> $inputs     Input data containing the source field value
     *
     * @return int Computed value (1 for "SI", 0 for any other value or when field is not present)
     *
     * @example
     * Input: $inputs = ['occasionalDriver' => 'SI']
     * Output: 1
     *
     * Input: $inputs = ['occasionalDriver' => 'NO']
     * Output: 0
     *
     * @internal
     */
    private function computeOccasionalDriver(FieldDefinition $definition, array $inputs): int
    {
        if (isset($inputs[$definition->getField()])) {
            return $inputs[$definition->getField()] === "SI" ? 1 : 0;
        }

        return 0;
    }

    /**
     * Computes values for all provided computed fields using their respective computation methods.
     *
     * @param array<FieldDefinition> $computed Array of field definitions for computed fields
     * @param array<string, mixed>   $inputs   Input data used for computing field values
     *
     * @return array<string, mixed> Array of computed values where keys are the mapped field
     *                             names and values are the computed results
     *
     * @throws BadMethodCallException When a computation method is not implemented for a field
     *
     * @throws FieldMapperException When a computed field definition is invalid
     * @example
     * ```php
     * $computed = [
     *     new FieldDefinition(
     *         field: 'occasionalDriver',
     *         mapsTo: 'occasional_driver_flag'
     *     )
     * ];
     * $inputs = ['occasionalDriver' => 'SI'];
     *
     * $result = $service->compute($computed, $inputs);
     * // Returns: ['occasional_driver_flag' => 1]
     * ```
     */
    public function compute(array $computed, array $inputs): array
    {
        $computedData = [];

        foreach ($computed as $definition) {
            if (!$definition instanceof FieldDefinition) {
                throw new FieldMapperException();
            }

            $method = sprintf('compute%s', ucfirst($definition->getField()));
            if (!method_exists($this, $method)) {
                throw new BadMethodCallException(sprintf('Method (%s) not implemented', $method));
            }

            $computedData[$definition->getMapsTo()] = $this?->$method($definition, $inputs);
        }

        return $computedData;
    }
}