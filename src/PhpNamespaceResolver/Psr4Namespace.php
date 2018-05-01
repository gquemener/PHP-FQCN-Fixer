<?php

namespace GildasQ\AutoloadFixer\PhpNamespaceResolver;

use GildasQ\AutoloadFixer\FileSystem\File;

final class Psr4Namespace implements PhpNamespaceResolver
{
    public function supports(File $file): bool
    {
        return 'psr-4' === $file->psr();
    }

    public function resolve(File $file): string
    {
        return $file->namespacePrefix() . ltrim(str_replace(DIRECTORY_SEPARATOR, '\\', dirname(
            str_replace($file->baseDirectory(), '', $file->path())
        )), '\\');
    }
}
