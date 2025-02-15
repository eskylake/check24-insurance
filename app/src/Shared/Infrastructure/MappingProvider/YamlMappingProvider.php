<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\MappingProvider;

use Symfony\Component\Yaml\Yaml;
use App\Shared\Domain\Exception\InputFileNotFoundException;
use App\Shared\Domain\MappingProvider\MappingProviderInterface;

final class YamlMappingProvider implements MappingProviderInterface
{
    public function getMappings(string $mappingPath): array
    {
        if (!file_exists($mappingPath)) {
            throw new InputFileNotFoundException($mappingPath);
        }

        return Yaml::parseFile($mappingPath);
    }
}
