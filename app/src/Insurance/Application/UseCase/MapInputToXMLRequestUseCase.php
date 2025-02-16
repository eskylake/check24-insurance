<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use Symfony\Component\Serializer\SerializerInterface;
use App\Insurance\Domain\Service\ComputedFieldServiceInterface;
use App\FieldMapping\Domain\Service\FieldMappingServiceInterface;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{
    public function __construct(
        private FieldMappingServiceInterface $fieldMappingService,
        private ComputedFieldServiceInterface $computedFieldService,
        private XmlStructureBuilderServiceInterface $xmlStructureBuilderService,
        private SerializerInterface $serializer,
    )
    {
    }

    public function execute(array $inputs, array $mappings): array
    {
        $processedFields = $this->fieldMappingService->processFields($inputs, $mappings);
        $comptedData = [];

        if ($processedFields->getMappedData()->getComputed()) {
            $comptedData = $this->computedFieldService->compute($processedFields->getMappedData()->getComputed(), $inputs);
        }


        $mappedData = [...$processedFields->getMappedData()->getMapped(), ...$comptedData];
        $structure = $this->xmlStructureBuilderService->buildNestedArray($mappedData, $processedFields->getFieldDefs());

        $a = $this->serializer->serialize($structure, 'xml', [
            'xml_root_node_name' => 'TarificacionThirdPartyRequest',
            'xml_encoding' => 'UTF-8',
            'remove_empty_tags' => true,
        ]);

        dd($a);
    }
}