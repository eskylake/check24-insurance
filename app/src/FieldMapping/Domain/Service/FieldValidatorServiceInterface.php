<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

interface FieldValidatorServiceInterface
{
    public function validate(array $data, array $fieldDefinitions): array;
}