<?php

declare(strict_types=1);

namespace App\Insurance\Domain\UseCase;

/**
 * Use case for transforming input data into XML requests using field mappings.
 */
interface MapInputToXMLRequestUseCaseInterface
{
    /**
     * Executes the process of transforming input data into an XML request.
     *
     * @param array $inputs                  Input data to be transformed, where keys are
     *                                       field names and values are the field values
     * @param array $mappings                Configuration array containing mapping definitions
     *                                       and XML structure information
     *
     * @return string Generated XML request string
     */
    public function execute(array $inputs, array $mappings): string;
}