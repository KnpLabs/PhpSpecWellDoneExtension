<?php

namespace spec\Knp\PhpSpec\WellDone\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoSpecLocatorSpec extends ObjectBehavior
{
    /**
     * @param Knp\PhpSpec\WellDone\Locator\ResourceInspector $inspector
     * @param Knp\PhpSpec\WellDone\Util\Filesystem $filesystem
     **/
    function let($inspector, $filesystem)
    {
        $inspector->getFilesystem()->willReturn($filesystem);

        $this->beConstructedWith($inspector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Locator\NoSpecLocator');
        $this->shouldHaveType('PhpSpec\Locator\PSR0\PSR0Locator');
    }

    function it_should_return_all_ressources()
    {
        $this->getAllResources()->shouldBeArray();
    }
}
