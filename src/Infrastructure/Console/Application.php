<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Psr\Container\ContainerInterface;
use Prooph\ServiceBus\CommandBus;
use PhpFQCNFixer\Infrastructure\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

final class Application extends BaseApplication
{
    const VERSION = '1.0.0-dev';

    private $container;

    public function __construct()
    {
        parent::__construct('PHP FQCN Fixer', self::VERSION);

        $this->container = (new ContainerBuilder())->build(new Container());

        $this->add(new FixCommand($this->container->get(CommandBus::class)));
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null !== $output) {
            $this->container->set(OutputInterface::class, $output);
        }

        return parent::run($input, $this->container->get(OutputInterface::class));
    }
}
