<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\File;

use Assert\Assertion;

class File
{
    private $path;
    private $content;

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

        if (isset($data['content'])) {
            $instance->content = $data['content'];
        }

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'content' => $this->content,
        ];
    }

    public function path(): string
    {
        return $this->path;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function withContent(string $content): File
    {
        $data = $this->toArray();
        $data['content'] = $content;

        return static::fromArray($data);
    }
}
