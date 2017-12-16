<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Listener;

use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;

final class FixClassname
{
    public function onEvent(FileFixingStarted $event): void
    {
        $expectedClassName = basename($event->file()->path(), '.php');

        $event->setContent(preg_replace(
            '/class (\w+)/',
            sprintf('class %s', $expectedClassName),
            $event->content()
        ));
    }
}
