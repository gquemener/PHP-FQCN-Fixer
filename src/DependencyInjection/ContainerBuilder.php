<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\DependencyInjection;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use PhpFQCNFixer\Analyze\PathChecker;
use PhpFQCNFixer\Analyze\InconsistencyFixer;
use PhpFQCNFixer\FileSystem\PhpFileLocator;
use PhpFQCNFixer\FileSystem\SymfonyFinderLocator;
use PhpFQCNFixer\Analyze\PhpParserFixer;
use PhpParser\ParserFactory;
use PhpFQCNFixer\PhpNamespaceResolver\PhpNamespaceResolver;
use PhpFQCNFixer\PhpNamespaceResolver\ComposerResolver;
use PhpFQCNFixer\PhpNamespaceResolver\Composer\DefaultLoader;
use PhpFQCNFixer\FileSystem\RealpathExpander;
use PhpFQCNFixer\PhpNamespaceResolver\Composer\Psr0NamespaceFinder;
use PhpFQCNFixer\PhpNamespaceResolver\Composer\Psr4NamespaceFinder;
use PhpFQCNFixer\Analyze\PhpParser\NamespaceVisitor;
use PhpFQCNFixer\Analyze\PhpParser\ClassnameVisitor;

final class ContainerBuilder
{
    public function build(): ContainerInterface
    {
        $container = new Container();

        $container->set(PhpFileLocator::class, new SymfonyFinderLocator());

        $resolver = new ComposerResolver();
        $loader = new DefaultLoader();
        $expander = new RealpathExpander();
        $resolver->addNamespaceFinder(new Psr0NamespaceFinder($loader, $expander));
        $resolver->addNamespaceFinder(new Psr4NamespaceFinder($loader, $expander));
        $container->set(PhpNamespaceResolver::class, $resolver);

        $fixer = new PhpParserFixer(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP7)
        );
        $fixer->addNodeVisitorFactory(new NamespaceVisitor($resolver));
        $fixer->addNodeVisitorFactory(new ClassnameVisitor());
        $container->set(InconsistencyFixer::class, $fixer);

        $container->set(PathChecker::class, new PathChecker(
            $container->get(PhpFileLocator::class),
            $container->get(InconsistencyFixer::class)
        ));

        return $container;
    }
}
