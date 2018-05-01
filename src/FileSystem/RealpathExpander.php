<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\FileSystem;

final class RealpathExpander implements PathExpander
{
    public function expand(string $path): string
    {
        if (false === $result = realpath($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Could not expand path "%s".',
                $path
            ));
        }

        return $result;
    }
}
