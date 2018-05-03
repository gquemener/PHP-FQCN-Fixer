<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Composer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use GildasQ\AutoloadFixer\FileSystem\File;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class FixAutoload extends BaseCommand
{
    private $container;

    protected function configure()
    {
        $this->setName('fix-autoload');
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composer = $this->getComposer();
        $configFile = $composer->getConfig()->getConfigSource()->getName();

        $config = json_decode(file_get_contents($configFile), true);
        if (null === $config) {
            throw new \RuntimeException(sprintf('Cannot decode "%s".', $configFile));
        }

        $fixer = $this->container->get('inconsistency_fixer');

        foreach ($config['autoload'] as $psr => $rules) {
            foreach ($rules as $namespace => $directory) {
                $directory = realpath($directory);
                if (false === $directory) {
                    continue;
                }

                $finder = (new Finder())->files()->name('*.php')->in($directory);

                foreach ($finder as $file) {
                    $file = File::open($file->getRealPath());
                    $file = $file->withPsr($psr);
                    $file = $file->withNamespacePrefix($namespace);
                    $file = $file->withBaseDirectory($directory);
                    $file = $fixer->fix($file);
                    $file->save();
                }
            }
        }
    }
}
