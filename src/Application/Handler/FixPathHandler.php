<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Handler;

use Prooph\ServiceBus\EventBus;
use PhpFQCNFixer\Application\Command\FixPath;
use PhpFQCNFixer\Model\PhpFileLocator\PhpFileLocator;
use PhpFQCNFixer\Model\File\File;
use PhpFQCNFixer\Model\File\Processor;
use PhpFQCNFixer\Model\File\Event;

final class FixPathHandler
{
    private $locator;
    private $fileProcessor;
    private $eventBus;

    public function __construct(
        PhpFileLocator $locator,
        Processor $fileProcessor,
        EventBus $eventBus
    ) {
        $this->locator = $locator;
        $this->fileProcessor = $fileProcessor;
        $this->eventBus = $eventBus;
    }

    public function handle(FixPath $command)
    {
        foreach ($this->locator->locateFiles($command->path()) as $filename) {
            $file = File::fromArray([
                'path' => $filename,
                'dumpContent' => $command->dumpContent(),
            ]);
            $this->eventBus->dispatch(new Event\FileFixingStarted([
                'file' => $file->toArray(),
            ]));
            $file = $this->fileProcessor->process($file);
            $this->eventBus->dispatch(new Event\FileFixingEnded([
                'file' => $file->toArray(),
            ]));
        }
    }
}
