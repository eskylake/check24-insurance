<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;
use App\Shared\Infrastructure\StaticHandler\StaticHandlerFactory;

class FieldStaticService implements FieldStaticServiceInterface
{
    public function __construct(private StaticHandlerFactory $staticHandlerFactory)
    {
    }

    public function handleInput(FieldDefinition $fieldDef): mixed
    {
        $staticValue = $fieldDef->getStatic();
        $handler = $this->staticHandlerFactory->getHandler($staticValue);

        return $handler?->handle(['format' => $fieldDef->getValidation()['format']]) ?: $staticValue;
    }
    public function handleOutput(FieldDefinition $fieldDef): mixed
    {
        $staticValue = $fieldDef->getStatic();
        $handler = $this->staticHandlerFactory->getHandler($staticValue);

        return $handler?->handle(['format' => $fieldDef->getValidation()['output_format']]) ?: $staticValue;
    }
}