<?php

namespace spec\Knp\PhpSpec\WellDone\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Util\Filesystem');
        $this->shouldHaveType('PhpSpec\Util\Filesystem');
    }

    function it_should_return_array_of_tokens()
    {
        $this->getTokens(dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/src/Knp/PhpSpec/WellDone/Extension.php')->shouldBeArray();
    }
}
