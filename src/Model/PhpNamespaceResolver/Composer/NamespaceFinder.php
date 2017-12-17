<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\PhpNamespaceResolver\Composer;

interface NamespaceFinder
{
    public function find(string $path): array;
}
