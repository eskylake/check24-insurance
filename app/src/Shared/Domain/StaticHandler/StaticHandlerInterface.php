<?php

declare(strict_types=1);

namespace App\Shared\Domain\StaticHandler;

interface StaticHandlerInterface
{
    public function handle(array $constraints = []): mixed;
}
