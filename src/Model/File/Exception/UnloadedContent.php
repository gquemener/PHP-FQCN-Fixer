<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\File\Exception;

use PhpFQCNFixer\Model\File\File;

final class UnloadedContent extends \RuntimeException
{
    public function __construct(File $file)
    {
        parent::__construct(sprintf(
            'Content of "%s" was not loaded.',
            $file->path()
        ));
    }
}
