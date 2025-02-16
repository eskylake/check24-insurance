<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException;

class EmptyInputFileException extends DomainException
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf("Input file can not be empty: %s", $filePath));
    }
}
