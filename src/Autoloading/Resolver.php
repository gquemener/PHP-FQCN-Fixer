<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\Autoloading;

use GildasQ\AutoloadFixer\FileSystem\File;

interface Resolver
{
    public function supports(File $file): bool;

    public function resolve(File $file): string;
}
