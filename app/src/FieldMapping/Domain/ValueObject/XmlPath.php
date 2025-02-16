<?php

declare(strict_types=1);

namespace App\FieldMapping\Domain\ValueObject;

/**
 * Represents a collection of XML paths with trimmed slashes.
 *
 * This value object manages a list of XML paths, ensuring each path
 * is trimmed of leading and trailing slashes for consistent representation.
 *
 * @final
 */
final class XmlPath
{
    /**
     * The array of trimmed XML paths.
     *
     * @var array<string>
     */
    private array $paths;

    /**
     * Constructs a new XmlPath instance.
     *
     * Trims leading and trailing slashes from each provided path.
     *
     * @param array<string> $paths The list of XML paths to be processed
     */
    public function __construct(array $paths)
    {
        $this->paths = array_map(
            fn(string $path) => trim($path, '/'),
            $paths
        );
    }

    /**
     * Retrieves the array of processed XML paths.
     *
     * @return array<string> The list of trimmed XML paths
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Creates an XmlPath instance from an array of paths.
     *
     * Static factory method to create a new XmlPath object.
     *
     * @param array<string> $paths The list of XML paths to be processed
     * @return self A new XmlPath instance
     */
    public static function fromArray(array $paths): self
    {
        return new self($paths);
    }
}