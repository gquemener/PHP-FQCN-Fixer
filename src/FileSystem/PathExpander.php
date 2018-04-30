<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\FileSystem;

interface PathExpander
{
    public function expand(string $path): string;
}
