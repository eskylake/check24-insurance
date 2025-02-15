<?php

declare(strict_types=1);

namespace App\Shared\Domain\InputParser;

interface InputParserInterface
{
    public function parse(string $inputPath): array;
}
