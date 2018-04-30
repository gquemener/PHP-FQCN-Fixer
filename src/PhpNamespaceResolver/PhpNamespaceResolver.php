<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\PhpNamespaceResolver;

use PhpFQCNFixer\FileSystem\File;

interface PhpNamespaceResolver
{
    public function resolve(File $file): string;
}
