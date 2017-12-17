<?php

namespace spec\PhpFQCNFixer\Infrastructure\PhpNamespaceResolver\Composer;

use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver\Composer\Psr0NamespaceFinder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpFQCNFixer\Model\PhpNamespaceResolver\Composer\ClassloaderLoader;
use Composer\Autoload\Classloader;
use PhpFQCNFixer\Model\File\PathExpander;

class Psr0NamespaceFinderSpec extends ObjectBehavior
{
    function let(ClassloaderLoader $loader, PathExpander $pathExpander)
    {
        $this->beConstructedWith($loader, $pathExpander);
    }

    function it_finds_all_possible_namespaces_based_on_classloader_psr0_prefixes(
        $loader,
        $pathExpander,
        Classloader $classloader
    ) {
        $loader->load('/data/src/App/Foo/Bar.php')->willReturn($classloader);
        $classloader->getPrefixes()->willReturn([
            'Vendor1' => ['/data/vendor/project1/src'],
            'App' => ['/data/src', '/data/vendor/app/src'],
        ]);
        $pathExpander->expand(Argument::any())->willReturnArgument(0);

        $this->find('/data/src/App/Foo/Bar.php')->shouldReturn([
            'App\\Foo'
        ]);
    }
}
