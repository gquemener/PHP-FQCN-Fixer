<?php

$loader = require(__DIR__.'/../vendor/autoload.php');

$prefixes = array_merge(
    $loader->getPrefixes(),
    $loader->getPrefixesPsr4()
);

function getPsr4ClassnameFinder(\Composer\Autoload\Classloader $loader)
{
    $prefixes = $loader->getPrefixesPsr4();

    return function ($filename) use ($prefixes)
    {
        foreach ($prefixes as $prefix => $dirs) {
            foreach ($dirs as $dir) {
                if (0 === strpos($filename, realpath($dir))) {
                    return sprintf(
                        '%s\\%s',
                        rtrim($prefix, '\\'),
                        str_replace(DIRECTORY_SEPARATOR, '\\', dirname(ltrim(
                            str_replace(realpath($dir), '', $filename),
                            DIRECTORY_SEPARATOR
                        )))
                    );
                }
            }
        }
    };
}

function getPsr0ClassnameFinder(\Composer\Autoload\Classloader $loader)
{
    $prefixes = $loader->getPrefixes();

    return function ($filename) use ($prefixes)
    {
        $availablePrefixes = [];
        foreach ($prefixes as $prefix => $dirs) {
            foreach ($dirs as $dir) {
                if (0 === strpos($filename, realpath($dir))) {
                    $availablePrefixes[] = [
                        'prefix' => $prefix,
                        'dir' => realpath($dir),
                    ];
                }
            }
        }

        if (empty($availablePrefixes)) {
            throw new \Exception('No prefix found');
        }

        if (count($availablePrefixes) > 1) {
            throw new \Exception(sprintf('%d classnames possible', count($availablePrefixes)));
        }

        return str_replace(DIRECTORY_SEPARATOR, '\\', dirname(ltrim(
            str_replace($availablePrefixes[0]['dir'], '', $filename),
            DIRECTORY_SEPARATOR
        )));
    };
}

$psr4ClassnameFinder = getPsr4ClassnameFinder($loader);
(var_dump($psr4ClassnameFinder('/home/gildas/projects/php-namespace-corrector/src/Application/FileReader.php')));

die;

$psr0ClassnameFinder = getPsr0ClassnameFinder($loader);
(var_dump($psr0ClassnameFinder('/home/gildas/projects/php-namespace-corrector/vendor/phpspec/phpspec/src/PhpSpec/CodeGenerator/Generator/Generator.php')));
