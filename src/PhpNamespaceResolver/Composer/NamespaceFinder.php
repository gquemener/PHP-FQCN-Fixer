<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\PhpNamespaceResolver\Composer;

interface NamespaceFinder
{
    public function find(string $path): array;
}
