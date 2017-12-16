<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Psr\Container\ContainerInterface;
use Prooph\ServiceBus\CommandBus;

final class Application extends BaseApplication
{
    const VERSION = '1.0.0-dev';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct('PHP FQCN Fixer', self::VERSION);

        $this->add(new FixCommand($container->get(CommandBus::class)));

        $this->setDefinition(new InputDefinition([
            new InputArgument('command_name', InputArgument::REQUIRED),
        ]));
    }
}
