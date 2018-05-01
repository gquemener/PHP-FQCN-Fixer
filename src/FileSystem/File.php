<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\FileSystem;

class File
{
    private $path;
    private $content;

    public function __construct(string $path, string $content)
    {
        $this->path = $path;
        $this->content = $content;
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
        return new self($this->path, $content);
    }
}
