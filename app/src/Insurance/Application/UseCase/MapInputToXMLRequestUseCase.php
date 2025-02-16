<?php

declare(strict_types=1);

namespace App\Insurance\Application\UseCase;

use RuntimeException;
use InvalidArgumentException;
use App\Insurance\Domain\ValueObject\Mapping;
use App\Insurance\Domain\ValueObject\MappedData;
use App\Insurance\Domain\Service\ComputedFieldServiceInterface;
use App\Insurance\Domain\Service\RequestBuilderServiceInterface;
use App\FieldMapping\Domain\Service\FieldMappingServiceInterface;
use App\FieldMapping\Domain\Service\XmlStructureBuilderServiceInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;

/**
 * Use case for transforming input data into XML requests using field mappings.
 *
 * This use case orchestrates the complete process of transforming input data into
 * an XML request through several steps:
 * 1. Field mapping and validation
 * 2. Computation of derived fields
 * 3. Building of XML structure
 * 4. Generation of final XML request
 *
 * @implements MapInputToXMLRequestUseCaseInterface
 *
 * @final
 */
final class MapInputToXMLRequestUseCase implements MapInputToXMLRequestUseCaseInterface
{
    /**
     * Constructs a new MapInputToXMLRequestUseCase instance.
     *
     * @param FieldMappingServiceInterface        $fieldMappingService        Service for processing field mappings
     * @param ComputedFieldServiceInterface       $computedFieldService       Service for computing derived fields
     * @param XmlStructureBuilderServiceInterface $xmlStructureBuilderService Service for building XML structure
     * @param RequestBuilderServiceInterface      $requestBuilderService      Service for building final XML request
     */
    public function __construct(
        private FieldMappingServiceInterface        $fieldMappingService,
        private ComputedFieldServiceInterface       $computedFieldService,
        private XmlStructureBuilderServiceInterface $xmlStructureBuilderService,
        private RequestBuilderServiceInterface      $requestBuilderService,
    )
    {
    }

    /**
     * Executes the process of transforming input data into an XML request.
     *
     * @param array<string, mixed> $inputs   Input data to be transformed, where keys are
     *                                       field names and values are the field values
     * @param array<string, mixed> $mappings Configuration array containing mapping definitions
     *                                       and XML structure information
     *
     * @return string Generated XML request string
     *
     * @throws RuntimeException If field processing or XML generation fails
     *
     * @throws InvalidArgumentException If mapping configuration is invalid
     * @example
     * ```php
     * $inputs = [
     *     'field1' => 'value1',
     *     'field2' => 'value2'
     * ];
     * $mappings = [
     *     'root' => 'Request',
     *     'field_definitions' => [
     *         'field1' => [
     *             'field' => 'field1',
     *             'maps_to' => 'mapped_field1'
     *         ]
     *     ]
     * ];
     *
     * $result = $useCase->execute($inputs, $mappings);
     * // Returns: <?xml version="1.0"?><Request><mapped_field1>value1</mapped_field1></Request>
     * ```
     */
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