<?php

declare(strict_types=1);

namespace App\Insurance\Domain\Command;

/**
 * Command handler for creating insurance requests from input data.
 */
interface CreateInsuranceRequestCommandInterface
{
    /**
     * Executes the insurance request creation process.
     *
     * @param string $inputPath Path to the input file containing the request data
     *
     * @return string Generated XML request string
     */
    public function execute(string $inputPath): string;
}
