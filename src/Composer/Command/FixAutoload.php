<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Composer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use GildasQ\AutoloadFixer\Analyze\PathChecker;

final class FixAutoload extends BaseCommand
{
    public function setPathChecker(PathChecker $checker)
    {
        $this->checker = $checker;

        return $this;
    }

    protected function configure()
    {
        $this
            ->setName('fix-autoload')
            ->addArgument('path', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checker->check($input->getArgument('path'));
    }
}
