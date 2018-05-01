<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\FileSystem;

interface PhpFileLocator
{
    public function locateFiles(string $path): \Iterator;
}
