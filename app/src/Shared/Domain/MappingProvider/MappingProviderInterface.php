<?php

declare(strict_types=1);

namespace App\Shared\Domain\MappingProvider;

/**
 * Provider for loading and parsing mapping configurations.
 */
interface MappingProviderInterface
{
    /**
     * Loads and parses a mapping configuration file.
     *
     * @param string $mappingPath Full path to the YAML mapping configuration file
     *
     * @return array<string, mixed> Parsed YAML content as an associative array
     */
    public function getMappings(string $mappingPath): array;
}
