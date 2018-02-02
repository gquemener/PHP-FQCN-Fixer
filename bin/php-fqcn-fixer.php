<?php

require __DIR__.'/../vendor/autoload.php';

$container = (new \PhpFQCNFixer\Infrastructure\DependencyInjection\ContainerBuilder())
    ->build(new \Symfony\Component\DependencyInjection\Container());
$application = new \PhpFQCNFixer\Infrastructure\Console\Application($container);

$application->run();
