<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\PhpFileLocator;

interface PhpFileLocator
{
    public function locateFiles(string $path): array;
}
