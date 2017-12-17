<?php

declare(strict_types = 1);

namespace PhpFQCNFixer\Application\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class FixPath extends Command
{
    use PayloadTrait;

    public function path(): string
    {
        return $this->payload()['path'];
    }
}
