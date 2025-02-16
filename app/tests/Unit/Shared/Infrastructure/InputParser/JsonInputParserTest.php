<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\InputParser;

use JsonException;
use App\Tests\Unit\TestCase;
use App\Shared\Infrastructure\InputParser\JsonInputParser;
use App\Shared\Domain\Exception\{
    InputFileNotFoundException,
    EmptyInputFileException,
};

class JsonInputParserTest extends TestCase
{
    private JsonInputParser $parser;
    private string $tempDir;

    protected function setUp(): void
    {
        $this->parser = new JsonInputParser();
        $this->tempDir = sys_get_temp_dir();
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->tempDir . '/test_*.json'));
    }

    private function createJsonFile(array $content): string
    {
        $filePath = $this->tempDir . '/test_' . uniqid() . '.json';
        file_put_contents($filePath, json_encode($content));
        return $filePath;
    }

    private function createFileWithContent(string $content): string
    {
        $filePath = $this->tempDir . '/test_' . uniqid() . '.json';
        file_put_contents($filePath, $content);
        return $filePath;
    }

    public function testParseValidJsonFile(): void
    {
        // Arrange
        $content = ['field1' => 'value1', 'field2' => 'value2'];
        $filePath = $this->createJsonFile($content);

        // Act
        $result = $this->parser->parse($filePath);

        // Assert
        $this->assertEquals($content, $result);
    }

    public function testParseNonexistentFile(): void
    {
        // Arrange
        $nonexistentPath = $this->tempDir . '/nonexistent.json';

        // Assert
        $this->expectException(InputFileNotFoundException::class);
        $this->expectExceptionMessage($nonexistentPath);

        // Act
        $this->parser->parse($nonexistentPath);
    }

    public function testParseEmptyFile(): void
    {
        // Arrange
        $filePath = $this->createJsonFile([]);

        // Assert
        $this->expectException(EmptyInputFileException::class);

        // Act
        $this->parser->parse($filePath);
    }

    public function testParseInvalidJson(): void
    {
        // Arrange
        $filePath = $this->createFileWithContent('{invalid:json}');

        // Assert
        $this->expectException(JsonException::class);

        // Act
        $this->parser->parse($filePath);
    }
}