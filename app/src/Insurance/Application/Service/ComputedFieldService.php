<?php

declare(strict_types=1);

namespace App\Insurance\Application\Service;

use BadMethodCallException;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\Insurance\Domain\Service\ComputedFieldServiceInterface;

final class ComputedFieldService implements ComputedFieldServiceInterface
{
    private function computeOccasionalDriver(FieldDefinition $definition, array $inputs): mixed
    {
        if (isset($inputs[$definition->getField()])) {
            return $inputs[$definition->getField()] === "SI" ? 1 : 0;
        }

        return 0;
    }

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