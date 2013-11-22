<?php

namespace spec\Knp\PhpSpec\WellDone\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceInspectorSpec extends ObjectBehavior
{
    /**
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource
     * @param Knp\PhpSpec\WellDone\Util\Filesystem $filesystem
     **/
    function let($resource, $filesystem)
    {
        $resource->getSpecFilename()->willReturn('spec/The/Path.php');
        $resource->getSrcFilename()->willReturn('src/The/Path.php');

        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Locator\ResourceInspector');
    }

    function it_should_return_filesystem($filesystem)
    {
        $this->getFilesystem()->shouldReturn($filesystem);
    }

    function it_should_detect_if_resource_has_spec($resource, $filesystem)
    {
        $filesystem->pathExists('spec/The/Path.php')->willReturn(true);

        $this->hasSpec($resource)->shouldReturn(true);
    }

    function it_should_detect_if_resource_has_no_spec($resource, $filesystem)
    {
        $filesystem->pathExists('spec/The/Path.php')->willReturn(false);

        $this->hasSpec($resource)->shouldReturn(false);
    }

    function it_should_detect_if_resource_is_a_class($resource, $filesystem)
    {
        $filesystem->getTokens('src/The/Path.php')->willReturn([
            [ 0 => T_CLASS ]
        ]);

        $this->isClass($resource)->shouldReturn(true);
    }

    function it_should_detect_if_resource_is_not_a_class($resource, $filesystem)
    {
        $filesystem->getTokens('src/The/Path.php')->willReturn([
            [ 0 => T_INTERFACE ]
        ]);

        $this->isClass($resource)->shouldReturn(false);
    }

    function it_should_detect_if_resource_is_abstract($resource, $filesystem)
    {
        $filesystem->getTokens('src/The/Path.php')->willReturn([
            [ 0 => T_ABSTRACT ]
        ]);

        $this->isAbstract($resource)->shouldReturn(true);
    }

    function it_should_detect_if_resource_is_not_abstract($resource, $filesystem)
    {
        $filesystem->getTokens('src/The/Path.php')->willReturn([
            [ 0 => T_INTERFACE ]
        ]);

        $this->isAbstract($resource)->shouldReturn(false);
    }
}
