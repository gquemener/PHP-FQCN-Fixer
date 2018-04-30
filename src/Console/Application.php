<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Psr\Container\ContainerInterface;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use PhpFQCNFixer\DependencyInjection\ContainerBuilder;
use PhpFQCNFixer\Analyze\PathChecker;

final class Application extends BaseApplication
{
    const VERSION = '0.0.1-dev';

    private $container;

    public function __construct()
    {
        parent::__construct('PHP FQCN Fixer', self::VERSION);

        $this->container = (new ContainerBuilder())->build();

        $this->add(new FixCommand($this->container->get(PathChecker::class)));
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null !== $output) {
            $this->container->set(OutputInterface::class, $output);
        }

        return parent::run($input, $output);
    }
}
