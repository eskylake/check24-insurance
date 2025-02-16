<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use RuntimeException;

/**
 * Exception thrown when an input file cannot be found.
 */
class InputFileNotFoundException extends RuntimeException
{
    /**
     * Constructs a new InputFileNotFoundException.
     *
     * @param string $filePath The path of the missing input file
     */
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf("Failed to read input file: %s", $filePath));
    }
}