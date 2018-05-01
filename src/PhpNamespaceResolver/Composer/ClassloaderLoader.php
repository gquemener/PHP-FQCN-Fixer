<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\PhpNamespaceResolver\Composer;

use Composer\Autoload\Classloader;

interface ClassloaderLoader
{
    public function load(string $path): Classloader;
}
