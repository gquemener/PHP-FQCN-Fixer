<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\PhpNamespaceResolver;

use PhpFQCNFixer\Model\File\File;

interface PhpNamespaceResolver
{
    public function resolve(File $file): string;
}
