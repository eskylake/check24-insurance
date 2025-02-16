<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;

interface FieldStaticServiceInterface
{
    public function handleInput(FieldDefinition $fieldDef): mixed;

    public function handleOutput(FieldDefinition $fieldDef): mixed;
}