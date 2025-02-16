<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Service\Prettier;

use App\Tests\Unit\TestCase;
use App\Shared\Application\Service\Prettier\XmlPrettier;

class XmlPrettierTest extends TestCase
{
    private XmlPrettier $prettier;

    protected function setUp(): void
    {
        $this->prettier = new XmlPrettier();
    }

    public function testPrettyWithSimpleXml(): void
    {
        // Arrange
        $input = '<root><child>value</child></root>';
        $expected = "<?xml version=\"1.0\"?>\n<root>\n  <child>value</child>\n</root>\n";

        // Act
        $result = $this->prettier->pretty($input);

        // Assert
        $this->assertEquals($expected, $result);
    }
}