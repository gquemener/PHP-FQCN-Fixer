<?php

namespace PhpFQCNFixer\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;

final class Application extends BaseApplication
{
    public function __construct($version)
    {
        parent::__construct('PHP FQCN Fixer', $version);

        $this->add(new FixCommand());
        $this->setDefinition(new InputDefinition([
            new InputArgument('command_name', InputArgument::REQUIRED),
        ]));
    }
}
