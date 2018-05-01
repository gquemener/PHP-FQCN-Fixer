<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\FileSystem;

interface PathExpander
{
    public function expand(string $path): string;
}
