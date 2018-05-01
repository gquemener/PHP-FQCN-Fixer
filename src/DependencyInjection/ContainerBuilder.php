<?php

declare(strict_types = 1);

namespace GildasQ\AutoloadFixer\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GildasQ\AutoloadFixer\Analyze\PhpParserFixer;
use PhpParser\ParserFactory;
use GildasQ\AutoloadFixer\Analyze\PhpParser\NamespaceVisitor;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\Psr0Namespace;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\Psr4Namespace;
use GildasQ\AutoloadFixer\Analyze\PhpParser\ClassnameVisitor;

final class ContainerBuilder
{
    public function build(ContainerInterface $container): ContainerInterface
    {
        $fixer = new PhpParserFixer(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP7)
        );

        $nsVisitor = new NamespaceVisitor();
        $nsVisitor->addResolver(new Psr0Namespace());
        $nsVisitor->addResolver(new Psr4Namespace());
        $fixer->addNodeVisitorFactory($nsVisitor);
        $fixer->addNodeVisitorFactory(new ClassnameVisitor());

        $container->set('inconsistency_fixer', $fixer);

        return $container;
    }
}
