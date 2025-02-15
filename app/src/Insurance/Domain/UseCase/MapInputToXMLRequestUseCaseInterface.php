<?php

namespace App\Insurance\Domain\UseCase;

interface MapInputToXMLRequestUseCaseInterface
{
    public function execute(array $inputs, array $mappings): array;
}