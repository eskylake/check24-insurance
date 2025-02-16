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

/**
 * Parser for reading and decoding JSON input files.
 *
 * This parser handles the reading and decoding of JSON files with comprehensive
 * error checking for file existence, readability, and content validity. It performs
 * the following validations:
 * - Checks if the file exists
 * - Verifies the file can be read
 * - Validates JSON syntax
 * - Ensures non-empty content
 *
 * @implements InputParserInterface
 *
 * @final
 */
final class JsonInputParser implements InputParserInterface
{
    /**
     * Parses a JSON file into an array structure.
     *
     * Reads the specified file and decodes its JSON content into a PHP array.
     * Performs several validation steps to ensure the input is valid and can be
     * processed correctly.
     *
     * @param string $inputPath Full path to the JSON file to be parsed
     *
     * @return array<string, mixed> Decoded JSON content as an associative array
     *
     * @throws FailedToReadFileException When the file cannot be read
     * @throws EmptyInputFileException When the file content is empty or decodes to empty
     * @throws JsonException When the JSON content is malformed or invalid
     *
     * @throws InputFileNotFoundException When the specified file does not exist
     * @example
     * ```php
     * $parser = new JsonInputParser();
     *
     * // Valid JSON file
     * $data = $parser->parse('/path/to/valid.json');
     * // Returns: ['key' => 'value']
     *
     * // Missing file
     * $parser->parse('/nonexistent.json');
     * // Throws: InputFileNotFoundException
     *
     * // Invalid JSON
     * $parser->parse('/path/to/invalid.json');
     * // Throws: JsonException
     * ```
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
