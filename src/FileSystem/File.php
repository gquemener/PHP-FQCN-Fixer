<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\FileSystem;

use GildasQ\AutoloadFixer\Analyze\InconsistencyFixer;

class File
{
    private $path;
    private $content;
    private $psr;
    private $namespacePrefix;
    private $baseDirectory;

    public function __construct(string $path, ?string $content = null, ?string $psr = null, ?string $namespacePrefix = null, ?string $baseDirectory = null)
    {
        $this->path = $path;
        $this->content = $content;
        $this->psr = $psr;
        $this->namespacePrefix = $namespacePrefix;
        $this->baseDirectory = $baseDirectory;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function psr(): ?string
    {
        return $this->psr;
    }

    public function namespacePrefix(): ?string
    {
        return $this->namespacePrefix;
    }

    public function baseDirectory(): ?string
    {
        return $this->baseDirectory;
    }

    public function withPath(string $path): File
    {
        return new self($path, $this->content, $this->psr, $this->namespacePrefix, $this->baseDirectory);
    }

    public function withContent(?string $content): File
    {
        return new self($this->path, $content, $this->psr, $this->namespacePrefix, $this->baseDirectory);
    }

    public function withPsr(?string $psr): File
    {
        return new self($this->path, $this->content, $psr, $this->namespacePrefix, $this->baseDirectory);
    }

    public function withNamespacePrefix(?string $namespacePrefix): File
    {
        return new self($this->path, $this->content, $this->psr, $namespacePrefix, $this->baseDirectory);
    }

    public function withBaseDirectory(?string $baseDirectory): File
    {
        return new self($this->path, $this->content, $this->psr, $this->namespacePrefix, $baseDirectory);
    }

    public static function open(string $path): self
    {
        return new self($path, file_get_contents($path));
    }

    public function save(): void
    {
        file_put_contents($this->path, $this->content);
    }
}
