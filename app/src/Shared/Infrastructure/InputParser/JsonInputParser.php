<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\InputParser;

use JsonException;
use App\Shared\Domain\InputParser\InputParserInterface;
use App\Shared\Domain\Exception\{
    InputFileNotFoundException,
    FailedToReadFileException,
    EmptyInputFileException,
};

final class JsonInputParser implements InputParserInterface
{
    /**
     * @throws JsonException
     */
    public function parse(string $inputPath): array
    {
        if (!file_exists($inputPath)) {
            throw new InputFileNotFoundException($inputPath);
        }

        $jsonContent = file_get_contents($inputPath);
        if ($jsonContent === false) {
            throw new FailedToReadFileException($inputPath);
        }

        $decodedContent = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

        if (empty($decodedContent)) {
            throw new EmptyInputFileException($inputPath);
        }

        return $decodedContent;
    }
}
