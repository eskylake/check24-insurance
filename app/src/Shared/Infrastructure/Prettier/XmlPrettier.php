<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Prettier;

use DOMDocument;
use App\Shared\Domain\Prettier\PrettierInterface;

final class XmlPrettier implements PrettierInterface
{
    public function pretty(string $output): false|string
    {
        $dom = new DOMDocument();
        $dom->loadXML($output);
        $dom->formatOutput = true;

        return $dom->saveXML();
    }
}
