<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Prooph\ServiceBus\CommandBus;
use PhpFQCNFixer\Application\Command\FixPath;
use Prooph\ServiceBus\Exception\MessageDispatchException;

final class FixCommand extends Command
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setName('fix')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to analyze')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandBus->dispatch(new FixPath([
            'path' => $input->getArgument('path'),
        ]));
    }
}
