<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\MappingProvider;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use App\Shared\Domain\Exception\InputFileNotFoundException;
use App\Shared\Domain\MappingProvider\MappingProviderInterface;

/**
 * Provider for loading and parsing YAML mapping configurations.
 *
 * @implements MappingProviderInterface
 *
 * @final
 */
final class YamlMappingProvider implements MappingProviderInterface
{
    /**
     * Loads and parses a YAML mapping configuration file.
     *
     * Reads the specified YAML file and converts it into a PHP array structure.
     * The method checks for file existence before attempting to parse the content.
     *
     * @param string $mappingPath Full path to the YAML mapping configuration file
     *
     * @return array<string, mixed> Parsed YAML content as an associative array
     *
     * @throws ParseException When the YAML content
     *         is malformed or cannot be parsed
     *
     * @throws InputFileNotFoundException When the specified mapping file does not exist
     * @example
     * ```php
     * $provider = new YamlMappingProvider();
     *
     * // Valid YAML file
     * $mappings = $provider->getMappings('/path/to/mappings.yaml');
     * // Returns: [
     * //     'root' => 'Request',
     * //     'field_definitions' => [
     * //         'field1' => [
     * //             'field' => 'field1',
     * //             'maps_to' => 'mapped_field1'
     * //         ]
     * //     ]
     * // ]
     *
     * // Missing file
     * $provider->getMappings('/nonexistent.yaml');
     * // Throws: InputFileNotFoundException
     *
     * // Invalid YAML
     * $provider->getMappings('/path/to/invalid.yaml');
     * // Throws: ParseException
     * ```
     */
    public function getMappings(string $mappingPath): array
    {
        if (!file_exists($mappingPath)) {
            throw new InputFileNotFoundException($mappingPath);
        }

        return Yaml::parseFile($mappingPath);
    }
}
