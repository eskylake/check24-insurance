<?php

declare(strict_types=1);

namespace App\Insurance\Domain\ValueObject;

use App\Insurance\Domain\Exception\FieldDefinitionException;

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

    public function toArray(): array
    {
        return [...$this->getMapped(), ...$this->getComputed()];
    }
}