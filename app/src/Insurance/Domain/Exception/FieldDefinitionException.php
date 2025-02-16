<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Exception;

use DomainException;

/**
 * Exception thrown when field definitions are not valid.
 *
 * This exception is used to indicate that the provided field definitions
 * do not meet the required validation criteria in the domain logic.
 */
class FieldDefinitionException extends DomainException
{
    /**
     * Constructs a new FieldDefinitionException.
     */
    public function __construct()
    {
        parent::__construct('Field definitions not valid');
    }
}