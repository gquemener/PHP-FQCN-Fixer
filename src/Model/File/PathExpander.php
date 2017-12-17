<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\File;

interface PathExpander
{
    public function expand(string $path): string;
}
