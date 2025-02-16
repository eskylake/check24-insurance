<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\DataObject\MappedData;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\FieldMapping\Domain\Service\{FieldMapperServiceInterface, FieldStaticServiceInterface};

/**
 * Service responsible for mapping field data according to provided field definitions.
 *
 * @implements FieldMapperServiceInterface
 */
class FieldMapperService implements FieldMapperServiceInterface
{
    /**
     * Constructs a new FieldMapperService instance.
     *
     * @param FieldStaticServiceInterface $fieldStaticService Service handling static field values
     */
    public function __construct(private FieldStaticServiceInterface $fieldStaticService)
    {
    }

    /**
     * Maps input data according to provided field definitions.
     *
     * This method processes each field definition and:
     * - Separates computed fields for later processing
     * - Applies static values when original data is missing
     * - Transforms values according to defined value mappings
     * - Skips fields that are neither present in input data nor have static values
     *
     * @param array $data Array of input data to be mapped
     * @param array $fieldDefinitions Array of field definitions that describe how to map the data
     *
     * @throws FieldMapperException When a field definition is invalid
     *
     * @return MappedData Data object containing mapped values and computed field definitions
     */
    public function map(array $data, array $fieldDefinitions): MappedData
    {
        $mapped = [];
        $computed = [];

        foreach ($fieldDefinitions as $definition) {
            if (!$definition instanceof FieldDefinition) {
                throw new FieldMapperException();
            }

            if ($definition->isComputed()) {
                $computed[] = $definition;
                continue;
            }

            if (!isset($data[$definition->getField()]) && !$definition->getStatic()) {
                continue;
            }

            $value = $data[$definition->getField()] ?? $this->fieldStaticService->handleOutput($definition);;

            if (isset($definition->getValues()[$value])) {
                $value = $definition->getValues()[$value];
            }

            $mapped[$definition->getMapsTo()] = $value;
        }

        return new MappedData($mapped, $computed);
    }
}