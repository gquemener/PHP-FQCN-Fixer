<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\InvokeStrategy\HandleCommandStrategy;
use Prooph\ServiceBus\Plugin\InvokeStrategy\OnEventStrategy;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\ServiceBus\Plugin\Router\EventRouter;
use Psr\Container\ContainerInterface;
use PhpFQCNFixer\Model\File\Processor;
use PhpFQCNFixer\Application\Command;
use PhpFQCNFixer\Application\Handler;
use PhpFQCNFixer\Infrastructure\File;
use PhpFQCNFixer\Infrastructure\PhpFileLocator\SymfonyFinderLocator;
use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver\ComposerResolver;
use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver\Composer\Psr0NamespaceFinder;
use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver\Composer\DefaultLoader;
use PhpFQCNFixer\Infrastructure\File\RealpathExpander;
use PhpFQCNFixer\Model\PhpFileLocator\PhpFileLocator;

final class ContainerBuilder
{
    public function build(Container $container): ContainerInterface
    {
        //$this->buildFileManipulator($container);
        //$this->buildEventBus($container);
        $this->buildPhpFileLocator($container);
        $this->buildFileProcessor($container);
        $this->buildCommandBus($container);

        return $container;
    }

    private function buildPhpFileLocator(Container $container): void
    {
        $container->set(PhpFileLocator::class, new SymfonyFinderLocator());
    }

    public function buildFileProcessor(Container $container): void
    {
        $resolver = new ComposerResolver();
        $resolver->addNamespaceFinder(new Psr0NamespaceFinder(new DefaultLoader(), new RealpathExpander()));

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
                $container->get(PhpFileLocator::class),
                $container->get(Processor::class)
            ),
        ]))->attachToMessageBus($instance);

        (new HandleCommandStrategy())->attachToMessageBus($instance);

        $container->set(CommandBus::class, $instance);
    }
}
