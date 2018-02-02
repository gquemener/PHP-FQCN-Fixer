<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Infrastructure\File;

use PhpFQCNFixer\Model\File;
use PhpFQCNFixer\Model\File\Exception\UnloadedContent;
use PhpFQCNFixer\Model\PhpNamespaceResolver\PhpNamespaceResolver;

final class FixNamespaceProcessor implements File\Processor
{
    private $processor;
    private $resolver;

    public function __construct(File\Processor $processor, PhpNamespaceResolver $resolver)
    {
        $this->processor = $processor;
        $this->resolver = $resolver;
    }

    public function process(File\File $file): File\File
    {
        if (null === $content = $file->content()) {
            throw new UnloadedContent($file);
        }
        $namespace = $this->resolver->resolve($file);

        return $this->processor->process(
            $file->withContent(preg_replace(
                '/namespace .*;/',
                sprintf('namespace %s;', $namespace),
                $content,
                1
            ))
        );
    }
}
