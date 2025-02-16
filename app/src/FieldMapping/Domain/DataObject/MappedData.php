<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\DataObject;

final class MappedData
{
    private array $mapped;

    private array $computed;

    public function __construct(
        array $mapped,
        array $computed,
    )
    {
        $this->mapped = $mapped;
        $this->computed = $computed;
    }

    public function getMapped(): array
    {
        return $this->mapped;
    }

    public function getComputed(): array
    {
        return $this->computed;
    }
}