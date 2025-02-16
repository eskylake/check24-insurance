<?php

declare(strict_types=1);

namespace App\Shared\Domain\Prettier;

interface PrettierInterface
{
    public function pretty(string $output): false|string;
}
