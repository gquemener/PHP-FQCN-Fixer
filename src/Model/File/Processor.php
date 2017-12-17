<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Model\File;

interface Processor
{
    public function process(File $file): File;
}
