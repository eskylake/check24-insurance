<?php

declare(strict_types=1);

namespace App\Tests\Unit\Insurance\Application\Command;

use App\Tests\Unit\TestCase;
use DomainException;
use RuntimeException;
use InvalidArgumentException;
use App\Insurance\Application\Command\CreateInsuranceRequestCommand;
use App\Shared\Domain\InputParser\InputParserInterface;
use App\Shared\Domain\MappingProvider\MappingProviderInterface;
use App\Insurance\Domain\UseCase\MapInputToXMLRequestUseCaseInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateInsuranceRequestCommandTest extends TestCase
{
    private InputParserInterface $inputParser;

    private MappingProviderInterface $mappingProvider;

    private MapInputToXMLRequestUseCaseInterface $mapInputToXMLRequestUseCase;

    private CreateInsuranceRequestCommand $command;

    private string $mappingPath = '/path/to/mapping.json';

    protected function setUp(): void
    {
        $params = $this->createMock(ParameterBagInterface::class);
        $this->inputParser = $this->createMock(InputParserInterface::class);
        $this->mappingProvider = $this->createMock(MappingProviderInterface::class);
        $this->mapInputToXMLRequestUseCase = $this->createMock(MapInputToXMLRequestUseCaseInterface::class);

        $params
            ->expects($this->once())
            ->method('get')
            ->with('app.acme_mapping_file')
            ->willReturn($this->mappingPath);

        $this->command = new CreateInsuranceRequestCommand(
            $params,
            $this->inputParser,
            $this->mappingProvider,
            $this->mapInputToXMLRequestUseCase,
        );
    }

    public function testExecuteSuccessfully(): void
    {
        // Arrange
        $inputPath = '/path/to/input.json';
        $parsedInput = ['field' => 'value'];
        $mappings = ['mappings' => []];
        $expectedXml = '<?xml version="1.0"?><request></request>';

        $this->inputParser
            ->expects($this->once())
            ->method('parse')
            ->with($inputPath)
            ->willReturn($parsedInput);

        $this->mappingProvider
            ->expects($this->once())
            ->method('getMappings')
            ->with($this->mappingPath)
            ->willReturn($mappings);

        $this->mapInputToXMLRequestUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($parsedInput, $mappings)
            ->willReturn($expectedXml);

        // Act
        $result = $this->command->execute($inputPath);

        // Assert
        $this->assertEquals($expectedXml, $result);
    }

    public function testExecuteWithParserException(): void
    {
        // Arrange
        $inputPath = '/path/to/invalid.json';

        $this->inputParser
            ->expects($this->once())
            ->method('parse')
            ->with($inputPath)
            ->willThrowException(new RuntimeException('Failed to parse input file'));

        $this->mappingProvider
            ->expects($this->never())
            ->method('getMappings');

        $this->mapInputToXMLRequestUseCase
            ->expects($this->never())
            ->method('execute');

        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse input file');

        // Act
        $this->command->execute($inputPath);
    }

    public function testExecuteWithMappingProviderException(): void
    {
        // Arrange
        $inputPath = '/path/to/input.json';
        $parsedInput = ['field' => 'value'];

        $this->inputParser
            ->expects($this->once())
            ->method('parse')
            ->with($inputPath)
            ->willReturn($parsedInput);

        $this->mappingProvider
            ->expects($this->once())
            ->method('getMappings')
            ->with($this->mappingPath)
            ->willThrowException(new InvalidArgumentException('Invalid mapping file'));

        $this->mapInputToXMLRequestUseCase
            ->expects($this->never())
            ->method('execute');

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid mapping file');

        // Act
        $this->command->execute($inputPath);
    }

    public function testExecuteWithUseCaseException(): void
    {
        // Arrange
        $inputPath = '/path/to/input.json';
        $parsedInput = ['field' => 'value'];
        $mappings = ['mappings' => []];

        $this->inputParser
            ->expects($this->once())
            ->method('parse')
            ->with($inputPath)
            ->willReturn($parsedInput);

        $this->mappingProvider
            ->expects($this->once())
            ->method('getMappings')
            ->with($this->mappingPath)
            ->willReturn($mappings);

        $this->mapInputToXMLRequestUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($parsedInput, $mappings)
            ->willThrowException(new DomainException('Failed to transform data'));

        // Assert
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Failed to transform data');

        // Act
        $this->command->execute($inputPath);
    }
}