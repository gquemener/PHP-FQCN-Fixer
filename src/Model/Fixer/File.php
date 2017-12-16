<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\Fixer;

use Assert\Assertion;

class File
{
    private $path;

    private function __construct(string $path)
    {
        Assertion::file($path);
        $this->path = $path;
    }

    public static function locatedAt(string $path): self
    {
        return new self($path);
    }

    public static function fromArray(array $data): self
    {
        $instance = self::locatedAt($data['path']);

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
        ];
    }

    public function path(): string
    {
        return $this->path;
    }
}
