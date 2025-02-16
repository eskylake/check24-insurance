<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Validator;

use PHPUnit\Framework\TestCase;
use App\Shared\Application\Service\Validator\IntegerValidator;
use App\Shared\Domain\DataObject\ValidationResult;

class IntegerValidatorTest extends TestCase
{
    private IntegerValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new IntegerValidator();
    }

    public function testValidateWithValidInteger(): void
    {
        // Arrange
        $value = 42;

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithValidFloat(): void
    {
        // Arrange
        $value = 42.5;

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithNumericString(): void
    {
        // Arrange
        $value = '42';

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithNonNumeric(): void
    {
        // Arrange
        $values = [
            'abc',
            '',
            [],
            new \stdClass(),
            null,
            true,
            false,
        ];

        foreach ($values as $value) {
            // Act
            $result = $this->validator->validate($value);

            // Assert
            $this->assertFalse($result->isValid(), "Value " . print_r($value, true) . " should be invalid");
            $this->assertEquals(['Must be a number'], $result->getErrors());
        }
    }

    public function testValidateWithMinConstraint(): void
    {
        // Arrange
        $value = 5;
        $constraints = ['min' => 1];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithMaxConstraint(): void
    {
        // Arrange
        $value = 5;
        $constraints = ['max' => 10];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithBelowMin(): void
    {
        // Arrange
        $value = 0;
        $min = 1;
        $constraints = ['min' => $min];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertFalse($result->isValid());
        $this->assertEquals([sprintf('Value must be >= %s', $min)], $result->getErrors());
    }

    public function testValidateWithAboveMax(): void
    {
        // Arrange
        $value = 11;
        $max = 10;
        $constraints = ['max' => $max];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertFalse($result->isValid());
        $this->assertEquals([sprintf('Value must be <= %s', $max)], $result->getErrors());
    }
}