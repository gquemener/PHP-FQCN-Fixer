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
    private $fileProcessor;

    public function __construct(
        PhpFileLocator $locator,
        Processor $fileProcessor
    ) {
        $this->locator = $locator;
        $this->fileProcessor = $fileProcessor;
    }

    public function handle(FixPath $command)
    {
        foreach ($this->locator->locateFiles($command->path()) as $filename) {
            $this->fileProcessor->process(File::locatedAt($filename));
        }
    }
}
