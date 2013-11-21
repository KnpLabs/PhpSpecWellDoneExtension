<?php

namespace spec\Knp\PhpSpec\WellDone;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Extension');
    }
}
