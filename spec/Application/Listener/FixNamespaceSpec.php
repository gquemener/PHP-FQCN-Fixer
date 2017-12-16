<?php

namespace spec\PhpFQCNFixer\Application\Listener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;
use PhpFQCNFixer\Model\Fixer\File;

class FixNamespaceSpec extends ObjectBehavior
{
    function it_fixes_namespace_on_event(
        FileFixingStarted $event,
        File $file
    ) {
        $event->file()->willReturn($file);
        $file->path()->willReturn('/data/www/src/App/Model/Foo.php');
        $event->content()->willReturn('<?php namespace App\Wrong;class Bar {}');

        $event->setContent('<?php namespace App\Model;class Bar {}')->shouldBeCalled();

        $this->onEvent($event);
    }
}
