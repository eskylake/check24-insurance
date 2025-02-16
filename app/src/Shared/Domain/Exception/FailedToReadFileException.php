<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use RuntimeException;

/**
 * Exception thrown when there is a failure in reading an input file.
 */
class FailedToReadFileException extends RuntimeException
{
    /**
     * Constructs a new FailedToReadFileException.
     *
     * @param string $filePath The path of the file that failed to be read
     */
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf("Failed to read input file: %s", $filePath));
    }
}