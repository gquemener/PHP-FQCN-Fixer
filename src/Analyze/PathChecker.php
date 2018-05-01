<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Analyze;

use Prooph\ServiceBus\EventBus;
use PhpFQCNFixer\FileSystem\PhpFileLocator;
use PhpFQCNFixer\FileSystem\File;

final class PathChecker
{
    private $locator;
    private $fixer;

    public function __construct(
        PhpFileLocator $locator,
        InconsistencyFixer $fixer
    ) {
        $this->locator = $locator;
        $this->fixer = $fixer;
    }

    public function check(string $path): void
    {
        foreach ($this->locator->locateFiles($path) as $filePath) {
            $file = $this->fixer->fix(
                new File($filePath, file_get_contents($filePath))
            );

            file_put_contents($file->path(), $file->content());
        }
    }
}
