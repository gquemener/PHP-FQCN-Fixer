<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Handler;

use PhpFQCNFixer\Application\Command\FixPath;
use PhpFQCNFixer\Model\PhpFileLocator\PhpFileLocator;
use PhpFQCNFixer\Model\File\File;
use PhpFQCNFixer\Model\File\Processor;

final class FixPathHandler
{
    private $locator;

    public function __construct(
        PhpFileLocator $locator,
        Processor $fileProcessor
    )
    {
        $this->locator = $locator;
        $this->fileProcessor = $fileProcessor;
    }

    public function handle(FixPath $command)
    {
        $path = $command->path();

        foreach ($this->locator->locateFiles($path) as $filename) {
            $this->fixFile(File::locatedAt($filename));
        }
    }

    private function fixFile(File $file): void
    {
        $processedFile = $this->fileProcessor->process($file);
    }
}
