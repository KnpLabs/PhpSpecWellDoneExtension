<?php

namespace spec\Knp\PhpSpec\WellDone\Console\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StatusCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Console\Command\StatusCommand');
    }
}
