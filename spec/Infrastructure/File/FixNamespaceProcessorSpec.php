<?php

namespace spec\PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Infrastructure\File\FixNamespaceProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpFQCNFixer\Model\File\Processor;
use PhpFQCNFixer\Model\PhpNamespaceResolver\PhpNamespaceResolver;
use PhpFQCNFixer\Model\File\File;
use PhpFQCNFixer\Model\File\Exception\UnloadedContent;

class FixNamespaceProcessorSpec extends ObjectBehavior
{
    function let(Processor $next, PhpNamespaceResolver $resolver)
    {
        $this->beConstructedWith($next, $resolver);
    }

    function it_is_a_file_processor()
    {
        $this->shouldBeAnInstanceOf(Processor::class);
    }

    function it_modifies_file_namespace_using_provided_resolver(
        File $file,
        File $modifiedFile,
        File $nextFile,
        PhpNamespaceResolver $resolver,
        $next
    ) {
        $file->content()->willReturn('<?php namespace Somewhere\\NotCorrect\\AtAll; class Bar {}');

        $resolver->resolve($file)->willReturn('App\\Foo');
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
