<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\DependencyInjection;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use GildasQ\AutoloadFixer\Analyze\PathChecker;
use GildasQ\AutoloadFixer\Analyze\InconsistencyFixer;
use GildasQ\AutoloadFixer\FileSystem\PhpFileLocator;
use GildasQ\AutoloadFixer\FileSystem\SymfonyFinderLocator;
use GildasQ\AutoloadFixer\Analyze\PhpParserFixer;
use PhpParser\ParserFactory;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\PhpNamespaceResolver;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\ComposerResolver;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\Composer\DefaultLoader;
use GildasQ\AutoloadFixer\FileSystem\RealpathExpander;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\Composer\Psr0NamespaceFinder;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\Composer\Psr4NamespaceFinder;
use GildasQ\AutoloadFixer\Analyze\PhpParser\NamespaceVisitor;
use GildasQ\AutoloadFixer\Analyze\PhpParser\ClassnameVisitor;
use GildasQ\AutoloadFixer\Console\FixCommand;
use Symfony\Component\EventDispatcher\EventDispatcher;
use GildasQ\AutoloadFixer\Composer\Command\FixAutoload;

final class ContainerBuilder
{
    // TODO (2018-05-01 11:40 by Gildas): Wrong interface (no set method)
    public function build(ContainerInterface $container): ContainerInterface
    {
        $container->set('php_file_locator', new SymfonyFinderLocator());

        $resolver = new ComposerResolver();
        $loader = new DefaultLoader();
        $expander = new RealpathExpander();
        $resolver->addNamespaceFinder(new Psr0NamespaceFinder($loader, $expander));
        $resolver->addNamespaceFinder(new Psr4NamespaceFinder($loader, $expander));
        $container->set('namespace_resolver', $resolver);

        $fixer = new PhpParserFixer(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP7)
        );
        $fixer->addNodeVisitorFactory(new NamespaceVisitor($resolver));
        $fixer->addNodeVisitorFactory(new ClassnameVisitor());
        $container->set('inconsistency_fixer', $fixer);

        $container->set('path_checker', new PathChecker(
            $container->get('php_file_locator'),
            $container->get('inconsistency_fixer')
        ));

        $container->set('console.commands', [
            (new FixAutoload())->setPathChecker($container->get('path_checker'))
        ]);

        return $container;
    }
}
