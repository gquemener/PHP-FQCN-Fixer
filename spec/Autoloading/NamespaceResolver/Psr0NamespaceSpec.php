<?php

namespace spec\GildasQ\AutoloadFixer\Autoloading\NamespaceResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GildasQ\AutoloadFixer\Autoloading\NamespaceResolver;
use GildasQ\AutoloadFixer\FileSystem\File;

class Psr0NamespaceSpec extends ObjectBehavior
{
    function it_is_a_php_namespace_resolver()
    {
        $this->shouldHaveType(NamespaceResolver::class);
    }

    function it_supports_psr0_file(File $file)
    {
        $file->psr()->willReturn('psr-0');

        $this->supports($file)->shouldReturn(true);
    }

    function it_does_not_support_psr4_file(File $file)
    {
        $file->psr()->willReturn('psr-4');

        $this->supports($file)->shouldReturn(false);
    }

    function it_resolves_namespace(File $file)
    {
        $file->baseDirectory()->willReturn('/root/src');
        $file->path()->willReturn('/root/src/App/Model/App.php');

        $this->resolve($file)->shouldReturn('App\\Model');
    }
}
