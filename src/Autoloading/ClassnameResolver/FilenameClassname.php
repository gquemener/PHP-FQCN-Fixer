<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;

use GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;
use GildasQ\AutoloadFixer\FileSystem\File;

final class FilenameClassname implements ClassnameResolver
{
    public function supports(File $file): bool
    {
        return 'psr-0' === $file->psr()
            && false === strpos($file->namespacePrefix(), '_');
    }

    public function resolve(File $file): string
    {
        return basename($file->path(), '.php');
    }
}
