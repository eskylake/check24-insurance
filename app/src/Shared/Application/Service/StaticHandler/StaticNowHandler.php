<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\StaticHandler;

use DateTimeImmutable;
use App\Shared\Domain\StaticHandler\StaticHandlerInterface;

class StaticNowHandler implements StaticHandlerInterface
{
    public function handle(?array $constraints = []): string
    {
        $format = $constraints['format'] ?? 'Y-m-d\\T00:00:00';
        return (new DateTimeImmutable())->format($format);
    }
}
