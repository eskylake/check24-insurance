<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\FieldMapping\Domain\ValueObject\XmlPath;
use App\FieldMapping\Domain\ValueObject\FieldDefinition;

abstract class TestCase extends BaseTestCase
{
    protected function createFieldDefinition(
        string $field,
        string $mapsTo,
        bool $required = false,
        array $validation = [],
        mixed $static = null,
        bool $computed = false,
        ?array $xmlPath = null,
        ?array $values = null,
        ?string $description = null,
    ): FieldDefinition {
        return new FieldDefinition(
            field: $field,
            mapsTo: $mapsTo,
            required: $required,
            validation: $validation,
            static: $static,
            computed: $computed,
            xmlPath: XmlPath::fromArray($xmlPath ?: ['sample/dummy']),
            values: $values,
            description: $description,
        );
    }
}