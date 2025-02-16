<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;

final class XmlStructureBuilderService implements XmlStructureBuilderServiceInterface
{
    public function buildNestedArray(array $mappedData, array $fieldDefs): array
    {
        $result = [];

        foreach ($fieldDefs as $field) {
            foreach ($field->getXmlPath()->getPaths() as $path) {
                $current = &$result;
                $segments = explode('/', $path);

                foreach ($segments as $segment) {
                    if (!isset($current[$segment])) {
                        $current[$segment] = [];
                    }
                    $current = &$current[$segment];
                }

                $current[$field->getMapsTo()] = $mappedData[$field->getMapsTo()];
            }
        }

        return $result;
    }
}