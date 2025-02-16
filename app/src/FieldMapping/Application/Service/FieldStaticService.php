<?php

declare(strict_types=1);

namespace App\FieldMapping\Application\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;
use App\FieldMapping\Domain\Service\FieldStaticServiceInterface;
use App\Shared\Infrastructure\StaticHandler\StaticHandlerFactory;

/**
 * Service for handling static field values with specialized formatting.
 *
 * @implements FieldStaticServiceInterface
 */
class FieldStaticService implements FieldStaticServiceInterface
{
    /**
     * Constructs a new FieldStaticService instance.
     *
     * @param StaticHandlerFactory $staticHandlerFactory Factory for creating static value handlers
     */
    public function __construct(private StaticHandlerFactory $staticHandlerFactory)
    {
    }

    /**
     * Processes static field values for input scenarios.
     *
     * @param FieldDefinition $fieldDef Field definition containing static value and validation rules
     *
     * @return mixed Processed static value or original value if no handler available
     */
    public function handleInput(FieldDefinition $fieldDef): mixed
    {
        $staticValue = $fieldDef->getStatic();
        $handler = $this->staticHandlerFactory->getHandler($staticValue);

        return $handler?->handle(['format' => $fieldDef->getValidation()['format']]) ?: $staticValue;
    }

    /**
     * Processes static field values for output scenarios.
     *
     * @param FieldDefinition $fieldDef Field definition containing static value and validation rules
     *
     * @return mixed Processed static value or original value if no handler available
     */
    public function handleOutput(FieldDefinition $fieldDef): mixed
    {
        $staticValue = $fieldDef->getStatic();
        $handler = $this->staticHandlerFactory->getHandler($staticValue);

        return $handler?->handle(['format' => $fieldDef->getValidation()['output_format']]) ?: $staticValue;
    }
}