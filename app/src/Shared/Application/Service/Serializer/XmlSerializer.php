<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Serializer;

use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Domain\Serializer\XmlSerializerInterface;

final class XmlSerializer implements XmlSerializerInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    public function serialize(string $root, mixed $data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'xml', [
            ...$context,
            'xml_root_node_name' => $root,
            'xml_encoding' => 'UTF-8'
        ]);
    }
}
