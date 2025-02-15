<?php

declare(strict_types=1);

namespace App\Insurance\Application\Command;

use App\Shared\Domain\InputParser\InputParserInterface;
use App\Shared\Domain\MappingProvider\MappingProviderInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;
use App\Insurance\Domain\Command\CreateInsuranceRequestCommandInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateInsuranceRequestCommand implements CreateInsuranceRequestCommandInterface
{
    private string $mappingPath;

    public function __construct(
        private ParameterBagInterface $params,
        private InputParserInterface $inputParser,
        private MappingProviderInterface $mappingProvider,
        private MapInputToXMLRequestUseCaseInterface $mapInputToXMLRequestUseCase,
    )
    {
        $this->mappingPath = $this->params->get('app.acme_mapping_file');
    }

    public function execute(string $inputPath): array
    {
        $inputs = $this->inputParser->parse($inputPath);
        $mappings = $this->mappingProvider->getMappings($this->mappingPath);

        return $this->mapInputToXMLRequestUseCase->execute($inputs, $mappings);
    }
}