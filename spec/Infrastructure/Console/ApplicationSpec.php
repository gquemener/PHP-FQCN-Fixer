<?php

namespace spec\PhpFQCNFixer\Infrastructure\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Application;
use Psr\Container\ContainerInterface;
use Prooph\ServiceBus\CommandBus;

class ApplicationSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, CommandBus $commandBus)
    {
        $this->beConstructedWith($container);
        $container->get(CommandBus::class)->willReturn($commandBus);
    }

    function it_is_a_symfony_application()
    {
        $this->shouldHaveType(Application::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('PHP FQCN Fixer');
    }

    function it_has_a_version()
    {
        $this->getVersion()->shouldReturn('1.0.0-dev');
    }

    function it_has_a_fix_fqcn_command()
    {
        $this->has('fix')->shouldReturn(true);
    }
}
