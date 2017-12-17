<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\Fixer\Event;

use Prooph\Common\Messaging\DomainEvent;
use PhpFQCNFixer\Model\File\File;

abstract class FileEvent extends DomainEvent
{
    private $file;

    public function __construct(array $payload)
    {
        $this->init();
        $this->setPayload($payload);
    }

    public function payload(): array
    {
        return [
            'file' => $this->file->toArray(),
        ];
    }

    protected function setPayload(array $payload): void
    {
        if (isset($payload['file'])) {
            $this->file = File::fromArray($payload['file']);
        }
    }

    public function file(): File
    {
        return $this->file;
    }
}
