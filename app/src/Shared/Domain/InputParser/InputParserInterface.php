<?php

declare(strict_types=1);

namespace App\Shared\Domain\InputParser;

/**
 * Parser for reading and decoding input files.
 */
interface InputParserInterface
{
    /**
     * Parses an input file into an array structure.
     *
     * @param string $inputPath Full path to the JSON file to be parsed
     *
     * @return array<string, mixed> Decoded JSON content as an associative array
     */
    public function parse(string $inputPath): array;
}
