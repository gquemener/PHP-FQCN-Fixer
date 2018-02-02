<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener\Output;

use Symfony\Component\Console\Output\OutputInterface;
use PhpFQCNFixer\Model\File\Event\FileFixingEnded;
use PhpFQCNFixer\Application\Listener\Output\StatCollector;

final class WriteResult
{
    private $output;
    private $collector;

    public function __construct(OutputInterface $output, StatCollector $collector)
    {
        $this->output = $output;
        $this->collector = $collector;
    }

    public function onEvent(FileFixingEnded $event): void
    {
        $file = $event->file();
        if ($this->output->isVerbose()) {
            $result = '<comment>i</comment>';
            if ($file->contentDumped()) {
                $result = '<info>u</info>';
            }
            $path = $file->path();
            $this->output->writeln(strtr(
                '[%result%] %path% <comment>%duration%ms</comment>',
                [
                    '%result%' => $result,
                    '%path%' => str_replace(getcwd().DIRECTORY_SEPARATOR, '', $path),
                    '%duration%' => $this->collector->getDuration($path),
                ]
            ));
        }
    }
}
