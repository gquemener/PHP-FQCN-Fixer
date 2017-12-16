<?php

namespace spec\PhpFQCNFixer\Infrastructure\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpFQCNFixer\Model\Fixer\Command\FixPath;

class FixCommandSpec extends ObjectBehavior
{
    function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    function it_is_a_symfony_command()
    {
        $this->shouldHaveType(Command::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('fix');
    }

    function it_has_a_required_path_argument()
    {
        $definition = $this->getDefinition();
        $definition->getArgument('path')->shouldBeRequired();
    }

    function it_dispatches_a_command_on_execution(
        InputInterface $input,
        OutputInterface $output,
        $commandBus
    ) {
        $input->bind(Argument::any())->shouldBeCalled();
        $input->validate()->shouldBeCalled();
        $input->isInteractive()->willReturn(false);
        $input->hasArgument('command')->willReturn(false);
        $input->getArgument('path')->willReturn('my_file');

        $commandBus->dispatch(Argument::allOf(
            Argument::type(FixPath::class),
            Argument::which('path', 'my_file')
        ))->shouldBeCalled();

        $this->run($input, $output);
    }
}
