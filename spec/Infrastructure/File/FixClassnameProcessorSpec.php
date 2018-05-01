<?php

namespace spec\PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Infrastructure\File\FixClassnameProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpFQCNFixer\Model\File\Processor;
use PhpFQCNFixer\Model\File\File;
use PhpFQCNFixer\Model\File\Exception\UnloadedContent;

class FixClassnameProcessorSpec extends ObjectBehavior
{
    function let(Processor $next)
    {
        $this->beConstructedWith($next);
    }

    function it_is_a_file_processor()
    {
        $this->shouldBeAnInstanceOf(Processor::class);
    }

    function it_resolves_expected_classname_from_filename(
        File $file,
        File $modifiedFile,
        File $nextFile,
        $next
    ) {
        $file->path()->willReturn('/data/src/App/Foo/Bar.php');
        $file->content()->willReturn('<?php namespace App\\Foo; class Wrong {}');
        $file->withContent('<?php namespace App\\Foo; class Bar {}')->willReturn($modifiedFile);

        $next->process($modifiedFile)->willReturn($nextFile);

        $this->process($file)->shouldReturn($nextFile);
    }

    function it_requires_file_content_to_be_loaded(File $file)
    {
        $file->path()->willReturn('/data/src/App/Foo/Bar.php');
        $file->content()->willReturn(null);

        $this->shouldThrow(new UnloadedContent($file->getWrappedObject()))->duringProcess($file);
    }
}
