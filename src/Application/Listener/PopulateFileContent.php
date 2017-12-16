<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener;

use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;
use PhpFQCNFixer\Application\FileReader;

final class PopulateFileContent
{
    private $reader;

    public function __construct(FileReader $reader)
    {
        $this->reader = $reader;
    }

    public function onEvent(FileFixingStarted $event): void
    {
        $file = $event->file();

        $event->setContent(
            $this->reader->read($file->path())
        );
    }
}
