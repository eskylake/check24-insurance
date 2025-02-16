<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\StaticHandler;

use DateTimeImmutable;
use App\Shared\Domain\StaticHandler\StaticHandlerInterface;

final class StaticNowHandler implements StaticHandlerInterface
{
    public function handle(?array $constraints = []): string
    {
        $format = $constraints['format'] ?? 'Y-m-d\\T00:00:00';
        return (new DateTimeImmutable())->format($format);
    }
}
