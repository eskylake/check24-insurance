<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;

final class XmlStructureBuilderService implements XmlStructureBuilderServiceInterface
{
    public function buildNestedArray(array $mappedData, array $fieldDefinitions): array
    {
        $result = [];

        foreach ($fieldDefinitions as $definition) {
            if (!$definition instanceof FieldDefinition) {
                throw new FieldMapperException();
            }

            foreach ($definition->getXmlPath()->getPaths() as $path) {
                $current = &$result;
                $segments = explode('/', $path);

                foreach ($segments as $segment) {
                    if (!isset($current[$segment])) {
                        $current[$segment] = [];
                    }
                    $current = &$current[$segment];
                }

                $current[$definition->getMapsTo()] = $mappedData[$definition->getMapsTo()];
            }
        }

        return $result;
    }
}