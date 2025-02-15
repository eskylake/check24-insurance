<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use DateTime;
use App\FieldMapping\Domain\Aggregate\FieldDefinition;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;

class FieldStaticService implements FieldStaticServiceInterface
{
    public function handleInput(FieldDefinition $fieldDef): mixed
    {
        $static = $fieldDef->getStatic();

        return match ($static) {
            'now' => (new DateTime())->format($fieldDef->getValidation()['format'] ?? 'Y-m-d'),
            default => $static,
        };
    }
    public function handleOutput(FieldDefinition $fieldDef): mixed
    {
        $static = $fieldDef->getStatic();

        return match ($static) {
            'now' => (new DateTime())->format($fieldDef->getValidation()['output_format'] ?? 'Y-m-d\\T00:00:00'),
            default => $static,
        };
    }
}