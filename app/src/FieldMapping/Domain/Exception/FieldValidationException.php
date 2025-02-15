<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Exception;

use DomainException;

class FieldValidationException extends DomainException
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Field validation failed');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}