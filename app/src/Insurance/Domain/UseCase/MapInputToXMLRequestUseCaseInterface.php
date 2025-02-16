<?php

declare(strict_types=1);

namespace App\Insurance\Domain\UseCase;

interface MapInputToXMLRequestUseCaseInterface
{
    public function execute(array $inputs, array $mappings): string;
}