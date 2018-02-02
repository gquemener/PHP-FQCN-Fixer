<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\DependencyInjection;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\InvokeStrategy;
use Prooph\ServiceBus\Plugin\Router;
use PhpFQCNFixer\Application\Command;
use PhpFQCNFixer\Application\Handler;
use PhpFQCNFixer\Application\Listener\Output;
use PhpFQCNFixer\Infrastructure\File;
use PhpFQCNFixer\Infrastructure\PhpFileLocator\SymfonyFinderLocator;
use PhpFQCNFixer\Infrastructure\PhpNamespaceResolver;
use PhpFQCNFixer\Model\File\Event;
use PhpFQCNFixer\Model\File\Processor;
use PhpFQCNFixer\Model\PhpFileLocator\PhpFileLocator;

final class ContainerBuilder
{
    public function build(Container $container): ContainerInterface
    {
        $this->buildOutput($container);
        $this->buildPhpFileLocator($container);
        $this->buildFileProcessor($container);
        $this->buildEventBus($container);
        $this->buildCommandBus($container);

        return $container;
    }

    private function buildOutput(Container $container): void
    {
        $container->set(OutputInterface::class, new ConsoleOutput());
    }

    private function buildPhpFileLocator(Container $container): void
    {
        $container->set(PhpFileLocator::class, new SymfonyFinderLocator());
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

    private function buildEventBus(Container $container): void
    {
        $collector = new Output\StatCollector();
        $eventBus = new EventBus();
        (new InvokeStrategy\OnEventStrategy())->attachToMessageBus($eventBus);
        (new Router\EventRouter([
            Event\FileFixingStarted::class => [
                $collector
            ],
            Event\FileFixingEnded::class => [
                $collector,
                new Output\WriteResult($container->get(OutputInterface::class), $collector),
            ],
        ]))->attachToMessageBus($eventBus);

        $container->set(EventBus::class, $eventBus);
    }

    private function buildCommandBus(Container $container): void
    {
        $instance = new CommandBus();

        (new Router\CommandRouter([
            Command\FixPath::class => new Handler\FixPathHandler(
                $container->get(PhpFileLocator::class),
                $container->get(Processor::class),
                $container->get(EventBus::class)
            ),
        ]))->attachToMessageBus($instance);

        (new InvokeStrategy\HandleCommandStrategy())->attachToMessageBus($instance);

        $container->set(CommandBus::class, $instance);
    }
}
