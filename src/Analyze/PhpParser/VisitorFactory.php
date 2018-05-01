<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Analyze\PhpParser;

use PhpParser\NodeVisitor;
use PhpFQCNFixer\FileSystem\File;

interface VisitorFactory
{
    public function create(File $file): NodeVisitor;
}
