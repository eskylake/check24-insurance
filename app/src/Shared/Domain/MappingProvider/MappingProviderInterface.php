<?php

declare(strict_types=1);

namespace App\Shared\Domain\MappingProvider;

interface MappingProviderInterface
{
    public function getMappings(string $mappingPath): array;
}
