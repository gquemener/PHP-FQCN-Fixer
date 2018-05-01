<?php

declare (strict_types = 1);

namespace PhpFQCNFixer\Analyze\PhpParser;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpFQCNFixer\Analyze\PhpParser\VisitorFactory;
use PhpFQCNFixer\FileSystem\File;
use PhpParser\NodeVisitor;
use PhpFQCNFixer\PhpNamespaceResolver\PhpNamespaceResolver;
use PhpParser\Node\Stmt\Class_;

final class ClassnameVisitor implements VisitorFactory
{
    public function create(File $file): NodeVisitor
    {
        $expected = basename($file->path(), '.php');

        return new class($expected) extends NodeVisitorAbstract
        {
            private $classname;

            public function __construct(string $classname)
            {
                $this->classname = $classname;
            }

            public function leaveNode(Node $node)
            {
                if (!$node instanceof Class_) {
                    return;
                }

                $node->name->name = $this->classname;
            }
        };
    }

}
