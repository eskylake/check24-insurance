<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\StaticHandler;

use App\Shared\Infrastructure\StaticHandler\StaticNowHandler;

class StaticHandlerFactory
{
    private array $handlers;

    public function __construct(private StaticNowHandler $staticNowHandler)
    {
        $this->handlers = [
            'now' => $this->staticNowHandler,
        ];
    }

    public function getHandler(mixed $staticValue): mixed
    {
        return $this->handlers[$staticValue] ?? $staticValue;
    }
}
