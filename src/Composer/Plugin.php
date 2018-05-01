<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Symfony\Component\DependencyInjection\Container;
use GildasQ\AutoloadFixer\Composer\Command\FixAutoload;
use GildasQ\AutoloadFixer\DependencyInjection\ContainerBuilder;

final class Plugin implements PluginInterface, Capable
{
    private $container;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->container = new Container();
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->build($this->container);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => 'GildasQ\AutoloadFixer\Composer\CommandProvider',
        ];
    }
}
