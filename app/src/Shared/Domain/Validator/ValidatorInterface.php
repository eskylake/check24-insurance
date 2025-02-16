<?php

declare(strict_types=1);

namespace App\Shared\Domain\Validator;

use App\Shared\Domain\DataObject\ValidationResult;

interface ValidatorInterface
{
    public function validate(mixed $value, array $constraints = []): ValidationResult;
}
