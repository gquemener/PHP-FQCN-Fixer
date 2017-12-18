<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\InvokeStrategy\HandleCommandStrategy;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Psr\Container\ContainerInterface;
use PhpFQCNFixer\Application\Command;
use PhpFQCNFixer\Application\Handler;
use PhpFQCNFixer\Infrastructure\File;
use PhpFQCNFixer\Infrastructure\PhpFileLocator;
use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver;
use PhpFQCNFixer\Model\File\Processor;

final class ContainerBuilder
{
    public function build(Container $container): ContainerInterface
    {
        $this->buildPhpFileLocator($container);
        $this->buildFileProcessor($container);
        $this->buildCommandBus($container);

        return $container;
    }

    private function buildPhpFileLocator(Container $container): void
    {
        $container->set(PhpFileLocator\PhpFileLocator::class, new PhpFileLocator\SymfonyFinderLocator());
    }

    public function buildFileProcessor(Container $container): void
    {
        $resolver = new PhpNamespaceResolver\ComposerResolver();
        $loader = new PhpNamespaceResolver\Composer\DefaultLoader();
        $expander = new File\RealpathExpander();
        $resolver->addNamespaceFinder(new PhpNamespaceResolver\Composer\Psr0NamespaceFinder($loader, $expander));
        $resolver->addNamespaceFinder(new PhpNamespaceResolver\Composer\Psr4NamespaceFinder($loader, $expander));

        $container->set(Processor::class, new File\LoadContentProcessor(
            new File\FixClassnameProcessor(
                new File\FixNamespaceProcessor(
                    new File\DumpContentProcessor(),
                    $resolver
                )
            )
        ));
    }

    private function buildCommandBus(Container $container): void
    {
        $instance = new CommandBus();

        (new CommandRouter([
            Command\FixPath::class => new Handler\FixPathHandler(
                $container->get(PhpFileLocator\PhpFileLocator::class),
                $container->get(Processor::class)
            ),
        ]))->attachToMessageBus($instance);

        (new HandleCommandStrategy())->attachToMessageBus($instance);

        $container->set(CommandBus::class, $instance);
    }
}
