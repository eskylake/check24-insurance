<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use App\Insurance\Domain\ValueObject\Mapping;
use App\Insurance\Domain\ValueObject\MappedData;
use App\Insurance\Domain\Service\ComputedFieldServiceInterface;
use App\Insurance\Domain\Service\RequestBuilderServiceInterface;
use App\FieldMapping\Domain\Service\FieldMappingServiceInterface;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{
    public function __construct(
        private FieldMappingServiceInterface        $fieldMappingService,
        private ComputedFieldServiceInterface       $computedFieldService,
        private XmlStructureBuilderServiceInterface $xmlStructureBuilderService,
        private RequestBuilderServiceInterface $requestBuilderService,
    )
    {
    }

    public function execute(array $inputs, array $mappings): string
    {
        $mapping = Mapping::fromArray($mappings);

        $processedFields = $this->fieldMappingService->processFields($inputs, $mapping->getFieldDefinitions());
        $computedData = $processedFields->getMappedData()->getComputed() ?
            $this->computedFieldService->compute($processedFields->getMappedData()->getComputed(), $inputs) :
            [];

        $mappedData = new MappedData($processedFields->getMappedData()->getMapped(), $computedData);
        $structure = $this->xmlStructureBuilderService->buildNestedArray($mappedData->toArray(), $processedFields->getFieldDefs());

        return $this->requestBuilderService->buildRequest($mapping, $structure);
    }
}