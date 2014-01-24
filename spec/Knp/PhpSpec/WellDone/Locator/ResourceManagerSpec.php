<?php

namespace spec\Knp\PhpSpec\WellDone\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Locator\ResourceManager');
    }
}
