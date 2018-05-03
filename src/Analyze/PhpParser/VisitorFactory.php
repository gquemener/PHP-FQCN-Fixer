<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze\PhpParser;

use PhpParser\NodeVisitor;
use GildasQ\AutoloadFixer\FileSystem\File;

interface VisitorFactory
{
    public function create(File $file): NodeVisitor;
}
