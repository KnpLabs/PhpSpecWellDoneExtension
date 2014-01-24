<?php

namespace spec\Knp\PhpSpec\WellDone\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceInspectorSpec extends ObjectBehavior
{
    /**
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource2
     * @param Knp\PhpSpec\WellDone\Util\Filesystem $filesystem
     **/
    function let($resource, $resource2, $filesystem)
    {
        $resource->getSpecFilename()->willReturn('spec/The/Path.php');
        $resource->getSrcFilename()->willReturn('src/The/Path.php');
        $resource->getSrcClassname()->willReturn('The\Path');

        $resource2->getSrcClassname()->willReturn('The\PathType');

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

    public function it_should_test_query_matching($resource)
    {
        $this->matchQuery($resource, '*Path')->shouldReturn(true);
        $this->matchQuery($resource, 'The\*')->shouldReturn(true);
        $this->matchQuery($resource, '*Pa*')->shouldReturn(true);
    }

    public function it_should_test_query_not_matching($resource)
    {
        $this->matchQuery($resource, 'Path')->shouldReturn(false);
        $this->matchQuery($resource, 'The/')->shouldReturn(false);
        $this->matchQuery($resource, '*path')->shouldReturn(false);
    }

    public function it_should_test_queries_matching($resource2)
    {
        $this->matchQueries($resource2, '*Type, App\*, *Controller')->shouldReturn(true);
        $this->matchQueries($resource2, '*Type, The\*, *Controller')->shouldReturn(true);
        $this->matchQueries($resource2, '*Type, The\*, *Path*')->shouldReturn(true);
    }

    public function it_should_test_queries_not_matching($resource2)
    {
        $this->matchQueries($resource2, '*Top, App\*, *Controller')->shouldReturn(false);
    }
}
