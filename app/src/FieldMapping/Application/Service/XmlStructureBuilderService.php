<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Exception\FieldMapperException;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;

/**
 * Service responsible for building nested array structures based on XML path definitions.
 *
 * This service takes mapped data and field definitions and constructs a nested array
 * structure that represents the desired XML hierarchy. It processes each field definition's
 * XML path and places the corresponding mapped data at the correct nested position.
 *
 * Example:
 * For a field definition with XML path "Root/Parent/Child" and mapped value "data",
 * it creates: ['Root' => ['Parent' => ['Child' => ['field_name' => 'data']]]]
 *
 * @implements XmlStructureBuilderServiceInterface
 *
 * @final
 */
final class XmlStructureBuilderService implements XmlStructureBuilderServiceInterface
{
    /**
     * Builds a nested array structure based on XML paths in field definitions.
     *
     * @param array<string, mixed> $mappedData      Associative array of mapped field values where
     *                                              keys are the mapped field names and values are
     *                                              the corresponding data
     * @param array<FieldDefinition> $fieldDefinitions Array of field definitions containing XML path
     *                                                and mapping information
     *
     * @throws FieldMapperException When a field definition is invalid or not an instance
     *                             of FieldDefinition class
     *
     * @return array<string, mixed> Nested array structure representing the XML hierarchy
     *                             with mapped values placed at appropriate positions
     *
     * @example
     * Input:
     *   $mappedData = ['sample_name' => 'Sample']
     *   $fieldDefinitions = [new FieldDefinition(
     *     field: 'name',
     *     mapsTo: 'sample_name',
     *     xmlPath: new XmlPath('Sample/PersonalInfo/Name')
     *   )]
     *
     * Output:
     *   [
     *     'Sample' => [
     *       'PersonalInfo' => [
     *         'Name' => [
     *           'sample_name' => 'Sample'
     *         ]
     *       ]
     *     ]
     *   ]
     */
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