<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Service;

interface ComputedFieldServiceInterface
{
    public function compute(array $computed, array $inputs): array;
}