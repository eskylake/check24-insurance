<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\StaticHandler;

use DateTimeImmutable;
use App\Shared\Domain\StaticHandler\StaticHandlerInterface;

/**
 * Handler for generating current date/time values with formatting.
 *
 * This handler implements the static value handling for 'now' tokens,
 * providing current date/time values with configurable formatting. It uses
 * PHP's DateTimeImmutable to ensure reliable date handling.
 *
 * Default format is 'Y-m-d\T00:00:00' which produces ISO-like dates
 * with zeroed time components.
 *
 * @implements StaticHandlerInterface
 *
 * @final
 */
class StaticNowHandler implements StaticHandlerInterface
{
    /**
     * Generates a formatted current date/time string.
     * @param array|null $constraints Optional array containing formatting options.
     *                                Expected to have a 'format' key with a valid
     *                                PHP date format string
     *
     * @return string Formatted current date/time string
     */
    public function handle(?array $constraints = []): string
    {
        $format = $constraints['format'] ?? 'Y-m-d\\T00:00:00';
        return (new DateTimeImmutable())->format($format);
    }
}
