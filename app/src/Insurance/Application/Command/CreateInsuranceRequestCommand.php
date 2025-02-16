<?php

declare(strict_types=1);

namespace App\Insurance\Application\Command;

use DomainException;
use RuntimeException;
use InvalidArgumentException;
use App\Shared\Domain\InputParser\InputParserInterface;
use App\Shared\Domain\MappingProvider\MappingProviderInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;
use App\Insurance\Domain\Command\CreateInsuranceRequestCommandInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Command handler for creating insurance requests from input data.
 *
 * This command is responsible for orchestrating the process of:
 * 1. Parsing input data from a specified file
 * 2. Loading field mappings from a configuration file
 * 3. Transforming the input data into an XML request using the mappings
 *
 * @implements CreateInsuranceRequestCommandInterface
 */
class CreateInsuranceRequestCommand implements CreateInsuranceRequestCommandInterface
{
    /**
     * Path to the mapping configuration file.
     *
     * @var string
     */
    private string $mappingPath;

    /**
     * Constructs a new CreateInsuranceRequestCommand instance.
     *
     * @param ParameterBagInterface                $params                      Configuration parameters container
     * @param InputParserInterface                 $inputParser                 Service for parsing input files
     * @param MappingProviderInterface             $mappingProvider             Service for providing field mappings
     * @param MapInputToXMLRequestUseCaseInterface $mapInputToXMLRequestUseCase UseCase for mapping input to XML
     */
    public function __construct(
        private ParameterBagInterface                $params,
        private InputParserInterface                 $inputParser,
        private MappingProviderInterface             $mappingProvider,
        private MapInputToXMLRequestUseCaseInterface $mapInputToXMLRequestUseCase,
    )
    {
        $this->mappingPath = $this->params->get('app.acme_mapping_file');
    }

    /**
     * Executes the insurance request creation process.
     *
     * @param string $inputPath Path to the input file containing the request data
     *
     * @return string Generated XML request string
     *
     * @throws InvalidArgumentException If mapping file is invalid
     * @throws DomainException If data transformation fails
     *
     * @throws RuntimeException If input file cannot be parsed
     * @example
     * ```php
     * $command = new CreateInsuranceRequestCommand($params, $parser, $provider, $useCase);
     * $xml = $command->execute('/path/to/input.json');
     * // Returns: <?xml version="1.0"?><request>...</request>
     * ```
     */
    public function execute(string $inputPath): string
    {
        $inputs = $this->inputParser->parse($inputPath);
        $mappings = $this->mappingProvider->getMappings($this->mappingPath);

        return $this->mapInputToXMLRequestUseCase->execute($inputs, $mappings);
    }
}