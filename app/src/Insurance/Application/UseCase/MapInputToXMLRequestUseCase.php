<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use Symfony\Component\Serializer\SerializerInterface;
use App\FieldMapping\Domain\Service\FieldMappingServiceInterface;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{
    public function __construct(
        private FieldMappingServiceInterface $fieldMappingService,
        private XmlStructureBuilderServiceInterface $xmlStructureBuilderService,
        private SerializerInterface $serializer,
    )
    {
    }

    public function execute(array $inputs, array $mappings): array
    {
        $processedFields = $this->fieldMappingService->processFields($inputs, $mappings);

        $structure = $this->xmlStructureBuilderService->buildNestedArray($processedFields->getMappedData(), $processedFields->getFieldDefs());

        $a = $this->serializer->serialize($structure, 'xml', [
            'xml_root_node_name' => 'TarificacionThirdPartyRequest',
            'xml_encoding' => 'UTF-8',
            'remove_empty_tags' => true,
        ]);

        dd($a);
    }
}