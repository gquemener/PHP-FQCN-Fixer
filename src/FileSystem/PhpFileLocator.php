<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\FileSystem;

interface PhpFileLocator
{
    public function locateFiles(string $path): \Iterator;
}
