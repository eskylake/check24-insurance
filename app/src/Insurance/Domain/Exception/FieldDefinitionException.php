<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Exception;

use DomainException;

class FieldDefinitionException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Field definitions not valid');
    }
}