<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\Prettier;

use DOMDocument;
use App\Shared\Domain\Prettier\PrettierInterface;

/**
 * Service for formatting XML strings with proper indentation and structure.
 *
 * @implements PrettierInterface
 *
 * @final
 */
final class XmlPrettier implements PrettierInterface
{
    /**
     * Formats an XML string for improved readability.
     *
     * @param string $output Raw XML string to be formatted
     *
     * @return string|false Formatted XML string with proper indentation and structure,
     *                     or false if the input cannot be parsed or formatted*
     */
    public function pretty(string $output): false|string
    {
        $dom = new DOMDocument();
        $dom->loadXML($output);
        $dom->formatOutput = true;

        return $dom->saveXML();
    }
}
