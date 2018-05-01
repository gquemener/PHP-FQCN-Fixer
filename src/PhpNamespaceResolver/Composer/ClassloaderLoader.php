<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\PhpNamespaceResolver\Composer;

use Composer\Autoload\Classloader;

interface ClassloaderLoader
{
    public function load(string $path): Classloader;
}
