<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze\PhpParser;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use GildasQ\AutoloadFixer\Analyze\PhpParser\VisitorFactory;
use GildasQ\AutoloadFixer\FileSystem\File;
use GildasQ\AutoloadFixer\Autoloading\NamespaceResolver;

final class NamespaceVisitor implements VisitorFactory
{
    private $resolvers = [];

    public function addResolver(NamespaceResolver $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    public function create(File $file): NodeVisitor
    {
        $expected = null;
        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports($file)) {
                $expected = $resolver->resolve($file);
                break;
            }
        }
        if (!$expected) {
            throw new \RuntimeException('No resolver found.');
        }

        return new class($expected) extends NodeVisitorAbstract
        {
            private $namespace;

            public function __construct(string $namespace)
            {
                $this->namespace = $namespace;
            }

            public function leaveNode(Node $node)
            {
                if (!$node instanceof Node\Stmt\Namespace_) {
                    return;
                }

                $node->name->parts = explode('\\', $this->namespace);
            }
        };
    }

}
