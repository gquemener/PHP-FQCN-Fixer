<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener\Output;

use Symfony\Component\Stopwatch\Stopwatch;
use PhpFQCNFixer\Model\File\Event;

final class StatCollector
{
    private $stopwatch;
    private $durations = [];

    public function __construct()
    {
        $this->stopwatch = new Stopwatch(true);
    }

    public function onEvent(Event\FileEvent $event): void
    {
        if ($event instanceof Event\FileFixingStarted) {
            $this->onFileFixingStarted($event);
        }

        if ($event instanceof Event\FileFixingEnded) {
            $this->onFileFixingEnded($event);
        }
    }

    public function getDuration(string $path): float
    {
        return $this->durations[$path];
    }

    private function onFileFixingStarted(Event\FileFixingStarted $event): void
    {
        $this->stopwatch->start($event->file()->path());
    }

    private function onFileFixingEnded(Event\FileFixingEnded $event): void
    {
        $this->durations[$event->file()->path()] = $this
            ->stopwatch
            ->stop($event->file()->path())
            ->getDuration();
    }
}
