<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\PhpNamespaceResolver\Composer;

use Composer\Autoload\Classloader;
use Symfony\Component\Finder\Finder;
use PhpFQCNFixer\PhpNamespaceResolver\Composer\ClassloaderLoader;

final class DefaultLoader implements ClassloaderLoader
{
    public function load(string $path): Classloader
    {
        do {
            $path = dirname($path);
            foreach ((new Finder())->directories()->in($path)->name('vendor') as $directory) {
                $autoloadPath = sprintf('%s/autoload.php', $directory->getRealPath());
                if (is_file($autoloadPath)) {
                    return require $autoloadPath;
                }
            }
        } while ($path !== '/');

        throw new \RuntimeException('Unable to locate the project vendor directory.');
    }
}
