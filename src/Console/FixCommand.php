<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpFQCNFixer\Analyze\PathChecker;

final class FixCommand extends Command
{
    private $pathChecker;

    public function __construct(PathChecker $pathChecker)
    {
        $this->pathChecker = $pathChecker;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fix')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to analyze.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->pathChecker->check($input->getArgument('path'));
    }
}
