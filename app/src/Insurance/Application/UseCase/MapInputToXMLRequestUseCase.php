<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use App\FieldMapping\Domain\Service\FieldMappingServiceInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{
    public function __construct(private FieldMappingServiceInterface $fieldMappingService)
    {
    }

    public function execute(array $inputs, array $mappings): array
    {
        $processedFields = $this->fieldMappingService->processFields($inputs, $mappings);

        dd($inputs, $processedFields);
    }
}