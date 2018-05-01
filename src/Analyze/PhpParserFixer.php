<?php

declare (strict_types = 1);

namespace GildasQ\AutoloadFixer\Analyze;

use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use GildasQ\AutoloadFixer\FileSystem\File;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Namespace_;
use GildasQ\AutoloadFixer\Analyze\PhpParser\VisitorFactory;

final class PhpParserFixer implements InconsistencyFixer
{
    private $factories = [];

    public function addNodeVisitorFactory(VisitorFactory $factory): void
    {
        $this->factories[] = $factory;
    }

    public function fix(File $file): File
    {
        $lexer = new Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $parser = new Parser\Php7($lexer);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NodeVisitor\CloningVisitor());

        foreach ($this->factories as $factory) {
            $traverser->addVisitor($factory->create($file));
        }

        $printer = new PrettyPrinter\Standard();

        $oldStmts = $parser->parse($file->content());
        $oldTokens = $lexer->getTokens();

        $newStmts = $traverser->traverse($oldStmts);

        $newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

        return $file->withContent($newCode);
    }
}
