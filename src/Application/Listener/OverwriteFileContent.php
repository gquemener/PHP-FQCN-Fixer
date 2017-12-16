<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener;

use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;
use PhpFQCNFixer\Application\FileWriter;

final class OverwriteFileContent
{
    private $writer;

    public function __construct(FileWriter $writer)
    {
        $this->writer = $writer;
    }

    public function onEvent(FileFixingStarted $event): void
    {
        $this->writer->write($event->file()->path(), $event->content());
    }
}
