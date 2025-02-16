<?php

declare(strict_types=1);

namespace App\Shared\Application\Service\StaticHandler;

/**
 * Factory for creating and managing static value handlers.
 *
 * This factory maintains a registry of handlers for processing static values
 * in field definitions. It currently supports:
 * - 'now' handler for date/time values
 *
 * The factory returns either a registered handler for known static values
 * or the original value if no handler is registered.
 *
 * @final
 */
class StaticHandlerFactory
{
    /**
     * @var array<string, object> Map of static value keys to their handlers
     */
    private array $handlers;

    /**
     * Constructs a new StaticHandlerFactory instance.
     *
     * @param StaticNowHandler $staticNowHandler Handler for 'now' static values
     */
    public function __construct(private StaticNowHandler $staticNowHandler)
    {
        $this->handlers = [
            'now' => $this->staticNowHandler,
        ];
    }

    /**
     * Retrieves a handler for the specified static value.
     *
     * @param mixed $staticValue Static value to find a handler for
     *
     * @return mixed Handler object for registered static values or the original
     *               value if no handler is found
     */
    public function getHandler(mixed $staticValue): mixed
    {
        return $this->handlers[$staticValue] ?? $staticValue;
    }
}
