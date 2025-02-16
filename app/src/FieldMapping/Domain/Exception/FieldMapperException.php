<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Exception;

use DomainException;

/**
 * Exception thrown when field mapping operations fail.
 *
 * @extends DomainException Represents a domain-specific error in field mapping
 */
class FieldMapperException extends DomainException
{
}