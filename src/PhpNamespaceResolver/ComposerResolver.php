<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\PhpNamespaceResolver;

use PhpFQCNFixer\PhpNamespaceResolver\PhpNamespaceResolver;
use PhpFQCNFixer\PhpNamespaceResolver\Exception\NamespaceUnresolvable;
use PhpFQCNFixer\PhpNamespaceResolver\Composer\NamespaceFinder;
use PhpFQCNFixer\FileSystem\File;

final class ComposerResolver implements PhpNamespaceResolver
{
    private $finders = [];

    public function addNamespaceFinder(NamespaceFinder $finder): void
    {
        $this->finders[] = $finder;
    }

    public function resolve(File $file): string
    {
        $path = $file->path();
        $possibleNamespaces = [];
        foreach ($this->finders as $finder) {
            $possibleNamespaces = array_merge($possibleNamespaces, $finder->find($path));
        }

        if (empty($possibleNamespaces)) {
            throw new NamespaceUnresolvable('No possible namespace was found in the Composer autoloader.');
        }

        if (count($possibleNamespaces) > 1) {
            throw new NamespaceUnresolvable(sprintf(
                '%d possible namespaces found: "%s"',
                count($possibleNamespaces),
                implode('", "', $possibleNamespaces)
            ));
        }

        return $possibleNamespaces[0];
    }
}
