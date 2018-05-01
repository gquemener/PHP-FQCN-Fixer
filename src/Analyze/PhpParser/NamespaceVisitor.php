<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze\PhpParser;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use GildasQ\AutoloadFixer\Analyze\PhpParser\VisitorFactory;
use GildasQ\AutoloadFixer\FileSystem\File;
use PhpParser\NodeVisitor;
use GildasQ\AutoloadFixer\PhpNamespaceResolver\PhpNamespaceResolver;

final class NamespaceVisitor implements VisitorFactory
{
    private $resolver;

    public function addResolver(PhpNamespaceResolver $resolver): void
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
                if (!$node instanceof Namespace_) {
                    return;
                }

                $node->name->parts = explode('\\', $this->namespace);
            }
        };
    }

}
