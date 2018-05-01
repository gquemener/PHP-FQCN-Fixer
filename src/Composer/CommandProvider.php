<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use GildasQ\AutoloadFixer\Composer\Command\FixAutoload;

final class CommandProvider implements CommandProviderCapability
{
    private $container;

    public function __construct(array $context)
    {
        $this->container = $context['plugin']->getContainer();
    }

    public function getCommands()
    {
        return $this->container->get('console.commands');
    }
}
