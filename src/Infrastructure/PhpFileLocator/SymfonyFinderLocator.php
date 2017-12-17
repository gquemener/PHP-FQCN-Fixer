<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\PhpFileLocator;

use PhpFQCNFixer\Model\PhpFileLocator\PhpFileLocator;
use Symfony\Component\Finder\Finder;

final class SymfonyFinderLocator implements PhpFileLocator
{
    public function locateFiles(string $path): array
    {
        if (is_file($path)) {
            return [realpath($path)];
        }

        if (is_dir($path)) {
            $filenames = [];
            $finder = (new Finder())->files()->name('*.php')->in($path);
            foreach ($finder as $file) {
                $filenames[] = $file->getRealPath();
            }

            return $filenames;
        }

        return [];
    }
}
