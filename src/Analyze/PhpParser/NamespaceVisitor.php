<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Analyze\PhpParser;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpFQCNFixer\Analyze\PhpParser\VisitorFactory;
use PhpFQCNFixer\FileSystem\File;
use PhpParser\NodeVisitor;
use PhpFQCNFixer\PhpNamespaceResolver\PhpNamespaceResolver;

final class NamespaceVisitor implements VisitorFactory
{
    private $resolver;

    public function __construct(PhpNamespaceResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function create(File $file): NodeVisitor
    {
        $expected = $this->resolver->resolve($file);

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
