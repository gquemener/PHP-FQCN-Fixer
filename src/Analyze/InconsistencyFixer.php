<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze;

use GildasQ\AutoloadFixer\FileSystem\File;

interface InconsistencyFixer
{
    public function fix(File $file): File;
}
