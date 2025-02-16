<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\MappingProvider;

use App\Tests\Unit\TestCase;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use App\Shared\Infrastructure\MappingProvider\YamlMappingProvider;
use App\Shared\Domain\Exception\InputFileNotFoundException;

class YamlMappingProviderTest extends TestCase
{
    private YamlMappingProvider $provider;

    private string $tempDir;

    protected function setUp(): void
    {
        $this->provider = new YamlMappingProvider();
        $this->tempDir = sys_get_temp_dir();
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->tempDir . '/test_*.yaml'));
    }

    private function arrayToYaml(array $content): string
    {
        return Yaml::dump($content, 6, 2);
    }

    private function createYamlFile(array $content): string
    {
        $filePath = $this->tempDir . '/test_' . uniqid() . '.yaml';
        file_put_contents($filePath, $this->arrayToYaml($content));
        return $filePath;
    }

    private function createFileWithContent(string $content): string
    {
        $filePath = $this->tempDir . '/test_' . uniqid() . '.yaml';
        file_put_contents($filePath, $content);
        return $filePath;
    }

    public function testGetMappingsWithValidYaml(): void
    {
        // Arrange
        $content = [
            'root' => 'Request',
            'field_definitions' => [
                'field1' => [
                    'field' => 'field1',
                    'maps_to' => 'mapped_field1',
                ],
            ],
        ];
        $filePath = $this->createYamlFile($content);

        // Act
        $result = $this->provider->getMappings($filePath);

        // Assert
        $this->assertEquals($content, $result);
    }

    public function testGetMappingsWithNonexistentFile(): void
    {
        // Arrange
        $nonexistentPath = $this->tempDir . '/nonexistent.yaml';

        // Assert
        $this->expectException(InputFileNotFoundException::class);
        $this->expectExceptionMessage($nonexistentPath);

        // Act
        $this->provider->getMappings($nonexistentPath);
    }

    public function testGetMappingsWithInvalidYaml(): void
    {
        // Arrange
        $filePath = $this->createFileWithContent("root: 'Request'\ninvalid_yaml:\n  - missing_colon\n  value");

        // Assert
        $this->expectException(ParseException::class);

        // Act
        $this->provider->getMappings($filePath);
    }
}