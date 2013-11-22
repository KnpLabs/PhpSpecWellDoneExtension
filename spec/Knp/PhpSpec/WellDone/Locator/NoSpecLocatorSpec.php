<?php

namespace spec\Knp\PhpSpec\WellDone\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoSpecLocatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Locator\NoSpecLocator');
    }

    function it_should_return_all_ressources()
    {
        $this->getAllResources()->shouldBeArray();
    }
}
