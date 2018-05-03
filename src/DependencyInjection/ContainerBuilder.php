<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PhpParser\ParserFactory;
use GildasQ\AutoloadFixer\Analyze\PhpParserFixer;
use GildasQ\AutoloadFixer\Analyze\PhpParser;
use GildasQ\AutoloadFixer\Autoloading;

final class ContainerBuilder
{
    public function build(ContainerInterface $container): ContainerInterface
    {
        $fixer = new PhpParserFixer(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP7)
        );

        $nsVisitor = new PhpParser\NamespaceVisitor();
        $nsVisitor->addResolver(new Autoloading\NamespaceResolver\Psr0Namespace());
        $nsVisitor->addResolver(new Autoloading\NamespaceResolver\Psr4Namespace());
        $fixer->addNodeVisitorFactory($nsVisitor);

        $cnVisitor = new PhpParser\ClassnameVisitor();
        $cnVisitor->addResolver(new Autoloading\ClassnameResolver\FilenameClassname());
        $cnVisitor->addResolver(new Autoloading\ClassnameResolver\UnderscoredClassname());
        $fixer->addNodeVisitorFactory($cnVisitor);

        $container->set('inconsistency_fixer', $fixer);

        return $container;
    }
}
