<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\Fixer\Handler;

use Prooph\ServiceBus\EventBus;
use PhpFQCNFixer\Model\Fixer\Command\FixPath;
use PhpFQCNFixer\Model\Fixer\File;
use PhpFQCNFixer\Model\Fixer\Event;

final class FixPathHandler
{
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(FixPath $command)
    {
        $file = File::locatedAt($command->path());

        $event = new Event\FileFixingStarted([
            'file' => $file->toArray(),
        ]);
        $this->eventBus->dispatch($event);

        $this->eventBus->dispatch(new Event\FileFixingEnded([
            'file' => $file->toArray(),
        ]));
    }
}
