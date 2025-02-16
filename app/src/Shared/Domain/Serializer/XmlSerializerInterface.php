<?php

declare(strict_types=1);

namespace App\Shared\Domain\Serializer;

interface XmlSerializerInterface
{
    public function serialize(string $root, mixed $data, array $context = []): string;
}
