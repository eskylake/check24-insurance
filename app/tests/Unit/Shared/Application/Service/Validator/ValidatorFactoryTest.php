<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Validator;

use App\Tests\Unit\TestCase;
use InvalidArgumentException;
use App\Shared\Application\Service\Validator\{
    ValidatorFactory,
    StringValidator,
    IntegerValidator,
    DateValidator
};

class ValidatorFactoryTest extends TestCase
{
    private StringValidator $stringValidator;

    private IntegerValidator $integerValidator;

    private DateValidator $dateValidator;

    private ValidatorFactory $factory;

    protected function setUp(): void
    {
        $this->stringValidator = $this->createMock(StringValidator::class);
        $this->integerValidator = $this->createMock(IntegerValidator::class);
        $this->dateValidator = $this->createMock(DateValidator::class);

        $this->factory = new ValidatorFactory(
            $this->stringValidator,
            $this->integerValidator,
            $this->dateValidator
        );
    }

    public function testGetValidatorWithStringType(): void
    {
        // Act
        $validator = $this->factory->getValidator('string');

        // Assert
        $this->assertSame($this->stringValidator, $validator);
    }

    public function testGetValidatorWithIntegerType(): void
    {
        // Act
        $validator = $this->factory->getValidator('integer');

        // Assert
        $this->assertSame($this->integerValidator, $validator);
    }

    public function testGetValidatorWithDateType(): void
    {
        // Act
        $validator = $this->factory->getValidator('date');

        // Assert
        $this->assertSame($this->dateValidator, $validator);
    }

    public function testGetValidatorWithUnknownType(): void
    {
        // Arrange
        $unknownType = 'unknown';

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('No validator found for type: %s', $unknownType));

        // Act
        $this->factory->getValidator($unknownType);
    }
}