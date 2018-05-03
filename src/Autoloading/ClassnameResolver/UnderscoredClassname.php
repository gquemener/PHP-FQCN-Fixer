<?php

namespace GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;

use GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;
use GildasQ\AutoloadFixer\FileSystem\File;

class UnderscoredClassname implements ClassnameResolver
{
    public function supports(File $file): bool
    {
        return 'psr-0' === $file->psr()
            && '_' === $file->namespacePrefix()[-1];
    }

    public function resolve(File $file): string
    {
        return str_replace(
            DIRECTORY_SEPARATOR,
            '_',
            ltrim(
                str_replace([$file->baseDirectory(), '.php'], '', $file->path()),
                DIRECTORY_SEPARATOR
            )
        );
    }
}
