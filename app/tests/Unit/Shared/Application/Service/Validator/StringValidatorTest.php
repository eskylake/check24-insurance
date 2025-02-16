<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Validator;

use PHPUnit\Framework\TestCase;
use App\Shared\Application\Service\Validator\StringValidator;
use App\Shared\Domain\DataObject\ValidationResult;

class StringValidatorTest extends TestCase
{
    private StringValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new StringValidator();
    }

    public function testValidateWithValidString(): void
    {
        // Arrange
        $value = 'test string';

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithEmptyString(): void
    {
        // Arrange
        $value = '';

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithAllowedValues(): void
    {
        // Arrange
        $value = 'allowed';
        $constraints = ['allowed_values' => ['allowed', 'also_allowed']];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithDisallowedValue(): void
    {
        // Arrange
        $value = 'not_allowed';
        $constraints = ['allowed_values' => ['allowed', 'also_allowed']];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertFalse($result->isValid());
        $this->assertEquals(['Not allowed value'], $result->getErrors());
    }

    public function testValidateWithNonString(): void
    {
        // Arrange
        $values = [
            123,
            1.23,
            true,
            [],
            new \stdClass(),
            null,
        ];

        foreach ($values as $value) {
            // Act
            $result = $this->validator->validate($value);

            // Assert
            $this->assertFalse($result->isValid());
            $this->assertEquals(['Must be a string'], $result->getErrors());
        }
    }
}