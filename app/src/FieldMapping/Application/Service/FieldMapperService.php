<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\DataObject\MappedData;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\FieldMapping\Domain\Service\FieldMapperServiceInterface;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;

class FieldMapperService implements FieldMapperServiceInterface
{
    public function __construct(private FieldStaticServiceInterface $fieldStaticService)
    {
    }

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