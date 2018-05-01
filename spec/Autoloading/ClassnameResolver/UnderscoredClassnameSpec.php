<?php

namespace spec\GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;
use GildasQ\AutoloadFixer\FileSystem\File;

class UnderscoredClassnameSpec extends ObjectBehavior
{
    function it_is_a_php_namespace_resolver()
    {
        $this->shouldHaveType(ClassnameResolver::class);
    }

    function it_supports_psr0_with_underscored_namespace_prefix(File $file)
    {
        $file->psr()->willReturn('psr-0');
        $file->namespacePrefix()->willReturn('App_');

        $this->supports($file)->shouldReturn(true);
    }

    function it_does_not_support_psr0_with_non_underscored_namespace_prefix(File $file)
    {
        $file->psr()->willReturn('psr-0');
        $file->namespacePrefix()->willReturn('App\\');

        $this->supports($file)->shouldReturn(false);
    }

    function it_does_not_support_psr4_file(File $file)
    {
        $file->psr()->willReturn('psr-4');

        $this->supports($file)->shouldReturn(false);
    }

    function it_resolves_classname(File $file)
    {
        $file->path()->willReturn('/root/src/App/Model/A.php');
        $file->namespacePrefix()->willReturn('App_');
        $file->baseDirectory()->willReturn('/root/src');

        $this->resolve($file)->shouldReturn('App_Model_A');
    }
}
