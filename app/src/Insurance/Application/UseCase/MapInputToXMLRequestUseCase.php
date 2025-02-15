<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{

    public function execute(array $inputs, array $mappings): array
    {
        dd($mappings, $inputs);
    }
}