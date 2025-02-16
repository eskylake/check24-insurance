<?php

declare(strict_types=1);

namespace App\Shared\Domain\StaticHandler;

/**
 * Handler for generating values.
 */
interface StaticHandlerInterface
{
    /**
     * Generates a value
     *
     * @param array $constraints
     *
     * @return mixed
     */
    public function handle(array $constraints = []): mixed;
}
