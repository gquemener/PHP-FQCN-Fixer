<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FixCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('fix')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to analyze')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
