<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator;

use InvalidArgumentException;
use App\Shared\Domain\Validator\ValidatorInterface;

final class ValidatorFactory
{
    private array $validators;

    public function __construct(
        private StringValidator  $stringValidator,
        private IntegerValidator $integerValidator,
        private DateValidator    $dateValidator,
    )
    {
        $this->validators = [
            'string' => $this->stringValidator,
            'integer' => $this->integerValidator,
            'date' => $this->dateValidator,
        ];
    }

    public function getValidator(string $type): ValidatorInterface
    {
        if (!isset($this->validators[$type])) {
            throw new InvalidArgumentException(sprintf("No validator found for type: %s", $type));
        }

        return $this->validators[$type];
    }
}
