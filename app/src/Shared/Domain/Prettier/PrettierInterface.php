<?php

declare(strict_types=1);

namespace App\Shared\Domain\Prettier;

/**
 * Interface for formatting XML strings with proper indentation and structure.
 */
interface PrettierInterface
{
    /**
     * Formats a string for improved readability.
     *
     * @param string $output Raw string to be formatted
     *
     * @return string|false Formatted string with proper indentation and structure,
     *                     or false if the input cannot be parsed or formatted*
     */
    public function pretty(string $output): false|string;
}
