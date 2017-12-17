<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Model\File;

final class LoadContentProcessor implements File\Processor
{
    private $processor;

    public function __construct(File\Processor $processor)
    {
        $this->processor = $processor;
    }

    public function process(File\File $file): File\File
    {
        return $this->processor->process(
            $file->withContent(file_get_contents($file->path()))
        );
    }
}
