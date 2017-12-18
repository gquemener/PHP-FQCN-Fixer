<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\File;

use Assert\Assertion;

class File
{
    private $path;
    private $dumpContent;
    private $content;
    private $contentDumped = false;

    private function __construct(string $path, bool $dumpContent)
    {
        Assertion::file($path);
        $this->path = $path;
        $this->dumpContent = $dumpContent;
    }

    public static function fromArray(array $data): self
    {
        $instance = new self($data['path'], $data['dumpContent']);

        if (isset($data['content'])) {
            $instance->content = $data['content'];
        }

        if (isset($data['contentDumped'])) {
            $instance->contentDumped = $data['contentDumped'];
        }

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'dumpContent' => $this->dumpContent,
            'content' => $this->content,
            'contentDumped' => $this->contentDumped,
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

    public function dumpContent(): bool
    {
        return $this->dumpContent;
    }

    public function withContent(string $content): File
    {
        $data = $this->toArray();
        $data['content'] = $content;

        return static::fromArray($data);
    }

    public function setContentDumped(bool $contentDumped): void
    {
        $this->contentDumped = $contentDumped;
    }

    public function contentDumped(): bool
    {
        return $this->contentDumped;
    }
}
