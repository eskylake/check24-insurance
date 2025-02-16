<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException;

/**
 * Exception thrown when an input file is empty.
 */
class EmptyInputFileException extends DomainException
{
    /**
     * Constructs a new EmptyInputFileException.
     *
     * @param string $filePath The path of the empty input file
     */
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf("Input file can not be empty: %s", $filePath));
    }
}