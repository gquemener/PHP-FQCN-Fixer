<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Model\File;

final class DumpContentProcessor implements File\Processor
{
    public function process(File\File $file): File\File
    {
        file_put_contents($file->path(), $file->content());

        return $file;
    }
}
