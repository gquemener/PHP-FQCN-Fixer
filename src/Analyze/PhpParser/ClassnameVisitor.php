<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze\PhpParser;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use GildasQ\AutoloadFixer\Analyze\PhpParser\VisitorFactory;
use GildasQ\AutoloadFixer\FileSystem\File;
use GildasQ\AutoloadFixer\Autoloading\ClassnameResolver;

final class ClassnameVisitor implements VisitorFactory
{
    private $resolvers = [];

    public function addResolver(ClassnameResolver $resolver): void
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
            private $classname;

            public function __construct(string $classname)
            {
                $this->classname = $classname;
            }

            public function leaveNode(Node $node)
            {
                if (!$node instanceof Node\Stmt\Class_) {
                    return;
                }

                $node->name->name = $this->classname;
            }
        };
    }

}
