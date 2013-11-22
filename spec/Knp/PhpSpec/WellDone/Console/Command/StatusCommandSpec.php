<?php

namespace spec\Knp\PhpSpec\WellDone\Console\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StatusCommandSpec extends ObjectBehavior
{
    /**
     * @param PhpSpec\Util\Filesystem $filesystem
     * @param Knp\PhpSpec\WellDone\Formater\ProgressFormater $formater
     **/
    function let($filesystem, $formater)
    {
        $this->beConstructedWith($filesystem, $formater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Console\Command\StatusCommand');
    }

    function it_should_be_well_named()
    {
        $this->getName()->shouldReturn('status');
    }
}
