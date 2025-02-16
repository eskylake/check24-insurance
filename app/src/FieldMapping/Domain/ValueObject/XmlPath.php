<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

final class XmlPath
{
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = array_map(
            fn(string $path) => trim($path, '/'),
            $paths
        );
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public static function fromArray(array $paths): self
    {
        return new self($paths);
    }
}