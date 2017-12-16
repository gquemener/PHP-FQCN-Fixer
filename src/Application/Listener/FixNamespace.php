<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener;

use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;

final class FixNamespace
{
    public function onEvent(FileFixingStarted $event): void
    {
        $path = $event->file()->path();
        $expectedNamespace = str_replace('/', '\\', substr(
            dirname($path),
            strpos($path, 'src') + 4
        ));

        $event->setContent(preg_replace(
            '/namespace (.*);/',
            sprintf('namespace %s;', $expectedNamespace),
            $event->content()
        ));
    }
}
