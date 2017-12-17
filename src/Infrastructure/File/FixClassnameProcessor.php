<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Model\File;
use PhpFQCNFixer\Model\File\Exception\UnloadedContent;

final class FixClassnameProcessor implements File\Processor
{
    private $processor;

    public function __construct(File\Processor $processor)
    {
        $this->processor = $processor;
    }

    public function process(File\File $file): File\File
    {
        if (null === $content = $file->content()) {
            throw new UnloadedContent($file);
        }

        $expectedClassName = basename($file->path(), '.php');

        return $this->processor->process(
            $file->withContent(preg_replace(
                '/class \w+/',
                sprintf('class %s', $expectedClassName),
                $content
            ))
        );
    }
}
