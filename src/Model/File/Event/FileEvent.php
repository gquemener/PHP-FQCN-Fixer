<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\Fixer\Event;

use Prooph\Common\Messaging\DomainEvent;
use PhpFQCNFixer\Model\Fixer\File;

abstract class FileEvent extends DomainEvent
{
    private $file;
    private $content;

    public function __construct(array $payload)
    {
        $this->init();
        $this->setPayload($payload);
    }

    public function payload(): array
    {
        return [
            'file' => $this->file->toArray(),
            'content' => $this->content,
        ];
    }

    protected function setPayload(array $payload): void
    {
        if (isset($payload['file'])) {
            $this->file = File::fromArray($payload['file']);
        }

        if (isset($payload['content'])) {
            $this->content = $payload['content'];
        }
    }

    public function file(): File
    {
        return $this->file;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function content(): ?string
    {
        return $this->content;
    }
}
