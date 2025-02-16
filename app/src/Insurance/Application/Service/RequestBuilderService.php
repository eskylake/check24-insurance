<?php

declare(strict_types=1);

namespace App\Insurance\Application\Service;

use App\Insurance\Domain\ValueObject\Mapping;
use App\Shared\Domain\Serializer\XmlSerializerInterface;
use App\Insurance\Domain\Service\RequestBuilderServiceInterface;

final class RequestBuilderService implements RequestBuilderServiceInterface
{
    public function __construct(
        private XmlSerializerInterface $serializer,
    )
    {
    }

    public function buildRequest(Mapping $mapping, array $structure): string
    {
        return $this->serializer->serialize($mapping->getRoot(), $structure);
    }
}