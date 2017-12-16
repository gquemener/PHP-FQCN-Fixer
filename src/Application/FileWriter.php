<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application;

interface FileWriter
{
    public function write(string $path, string $content): void;
}
