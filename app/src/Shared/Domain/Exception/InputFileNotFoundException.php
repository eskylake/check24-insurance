<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use RuntimeException;

class InputFileNotFoundException extends RuntimeException
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf("Failed to read input file: %s", $filePath));
    }
}
