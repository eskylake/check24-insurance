<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Command;

interface CreateInsuranceRequestCommandInterface
{
    public function execute(string $inputPath): string;
}
