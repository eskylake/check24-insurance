<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Service;

use App\Insurance\Domain\ValueObject\Mapping;

interface RequestBuilderServiceInterface
{
    public function buildRequest(Mapping $mapping, array $structure): string;
}