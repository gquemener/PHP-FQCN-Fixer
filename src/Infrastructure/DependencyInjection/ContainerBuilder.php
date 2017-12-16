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
use PhpFQCNFixer\Model\Fixer\Command\FixPath;
use PhpFQCNFixer\Model\Fixer\Handler\FixPathHandler;
use PhpFQCNFixer\Model\Fixer\Event\FileFixingStarted;
use PhpFQCNFixer\Application\Listener;
use PhpFQCNFixer\Application\FileManipulator\Basic;

final class ContainerBuilder
{
    public function build(Container $container): ContainerInterface
    {
        $this->buildFileManipulator($container);
        $this->buildEventBus($container);
        $this->buildCommandBus($container);

        return $container;
    }

    public function buildFileManipulator(Container $container)
    {
        $container->set(Basic::class, new Basic());

    }

    private function buildEventBus(Container $container): void
    {
        $instance = new EventBus();
        $fileManipulator = $container->get(Basic::class);

        (new EventRouter([
            FileFixingStarted::class => [
                new Listener\PopulateFileContent($fileManipulator),
                new Listener\FixClassname(),
                new Listener\FixNamespace(),
                new Listener\OverwriteFileContent($fileManipulator),
            ]
        ]))->attachToMessageBus($instance);

        (new OnEventStrategy())->attachToMessageBus($instance);

        $container->set(EventBus::class, $instance);
    }

    private function buildCommandBus(Container $container): void
    {
        $instance = new CommandBus();

        (new CommandRouter([
            FixPath::class => new FixPathHandler($container->get(EventBus::class)),
        ]))->attachToMessageBus($instance);

        (new HandleCommandStrategy())->attachToMessageBus($instance);

        $container->set(CommandBus::class, $instance);
    }
}
