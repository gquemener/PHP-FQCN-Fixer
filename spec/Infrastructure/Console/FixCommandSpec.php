<?php

namespace spec\PhpFQCNFixer\Infrastructure\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;

class FixCommandSpec extends ObjectBehavior
{
    function it_is_a_symfony_command()
    {
        $this->shouldHaveType(Command::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('fix');
    }
}
