<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\FileManipulator;

use PhpFQCNFixer\Application\FileReader;
use PhpFQCNFixer\Application\FileWriter;

final class Basic implements FileReader, FileWriter
{
    public function read(string $path): string
    {
        return file_get_contents($path);
    }

    public function write(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }
}
