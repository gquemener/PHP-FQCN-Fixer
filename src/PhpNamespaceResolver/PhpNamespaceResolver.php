<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\PhpNamespaceResolver;

use GildasQ\AutoloadFixer\FileSystem\File;

interface PhpNamespaceResolver
{
    public function supports(File $file): bool;

    public function resolve(File $file): string;
}
