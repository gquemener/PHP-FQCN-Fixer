<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Analyze;

use PhpFQCNFixer\FileSystem\File;

interface InconsistencyFixer
{
    public function fix(File $file): File;
}
