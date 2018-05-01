<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\FileSystem;

use Symfony\Component\Finder\Finder;

final class SymfonyFinderLocator implements PhpFileLocator
{
    public function locateFiles(string $path): \Iterator
    {
        if (is_file($path)) {
            yield realpath($path);
        }

        if (is_dir($path)) {
            $filenames = [];
            $finder = (new Finder())->files()->name('*.php')->in($path);
            foreach ($finder as $file) {
                yield $file->getRealPath();
            }
        }
    }
}
