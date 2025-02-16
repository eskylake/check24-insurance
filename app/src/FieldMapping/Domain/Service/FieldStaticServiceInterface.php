<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\Service;

use App\FieldMapping\Domain\ValueObject\FieldDefinition;

/**
 * Interface for handling static field values with specialized formatting.
 */
interface FieldStaticServiceInterface
{

    /**
     * Processes static field values for input scenarios.
     *
     * @param FieldDefinition $fieldDef Field definition containing static value and validation rules
     *
     * @return mixed Processed static value or original value if no handler available
     */
    public function handleInput(FieldDefinition $fieldDef): mixed;

    /**
     * Processes static field values for output scenarios.
     *
     * @param FieldDefinition $fieldDef Field definition containing static value and validation rules
     *
     * @return mixed Processed static value or original value if no handler available
     */
    public function handleOutput(FieldDefinition $fieldDef): mixed;
}