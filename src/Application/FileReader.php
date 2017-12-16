<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application;

interface FileReader
{
    public function read(string $path): string;
}
