<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Validator;

use PHPUnit\Framework\TestCase;
use DateTime;
use App\Shared\Application\Service\Validator\DateValidator;

class DateValidatorTest extends TestCase
{
    private DateValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new DateValidator();
    }

    public function testValidateWithDefaultFormat(): void
    {
        // Arrange
        $value = '2025-02-16';

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithCustomFormat(): void
    {
        // Arrange
        $value = '17/02/2025';
        $constraints = ['format' => 'd/m/Y'];

        // Act
        $result = $this->validator->validate($value, $constraints);

        // Assert
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidateWithInvalidDate(): void
    {
        // Arrange
        $value = 'invalid-date';

        // Act
        $result = $this->validator->validate($value);

        // Assert
        $this->assertFalse($result->isValid());
        $this->assertEquals(['Invalid date format'], $result->getErrors());
    }
}