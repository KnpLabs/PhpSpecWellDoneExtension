<?php

namespace spec\Knp\PhpSpec\WellDone\Formater;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProgressFormaterSpec extends ObjectBehavior
{
    /**
     * @param PhpSpec\Util\Filesystem $filesystem
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource1
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource2
     * @param PhpSpec\Locator\PSR0\PSR0Resource $resource3
     **/
    function let($filesystem, $resource1, $resource2, $resource3)
    {
        $this->beConstructedWith($filesystem);

        $resource1->getSpecFilename()->willReturn('/home/user/php/spec/File1Spec.php');
        $resource1->getSrcClassname()->willReturn('Knp\File1');
        $resource2->getSpecFilename()->willReturn('/home/user/php/spec/File2Spec.php');
        $resource2->getSrcClassname()->willReturn('Knp\File2');
        $resource3->getSpecFilename()->willReturn('/home/user/php/spec/File3Spec.php');
        $resource3->getSrcClassname()->willReturn('Knp\File3');

        $filesystem->pathExists(Argument::any())->willReturn(false);
        $filesystem->pathExists('/home/user/php/spec/File1Spec.php')->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\PhpSpec\WellDone\Formater\ProgressFormater');
    }

    function it_should_build_a_progress_bar($resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];

        $expected  = sprintf('<fg=black;bg=green>%s</fg=black;bg=green>', str_pad('1', 17, " ",STR_PAD_BOTH));
        $expected .= sprintf('<error>%s</error>', str_pad('2', 33, " ", STR_PAD_BOTH));

        $this->buildProgressBar($resources)->shouldReturn($expected);
    }

    function it_should_rebuild_a_progress_bar($filesystem, $resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];
        $filesystem->pathExists('/home/user/php/spec/File2Spec.php')->willReturn(true);

        $expected  = sprintf('<fg=black;bg=green>%s</fg=black;bg=green>', str_pad('2', 33, " ",STR_PAD_BOTH));
        $expected .= sprintf('<error>%s</error>', str_pad('1', 17, " ", STR_PAD_BOTH));

        $this->buildProgressBar($resources)->shouldReturn($expected);
    }

    function it_should_build_state_string($resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];

        $this->buildState($resources)->shouldReturn('<fg=green;options=bold>3 classes</fg=green;options=bold>    <fg=green;options=bold>1 spec</fg=green;options=bold>    <fg=red;options=bold>2 missings</fg=red;options=bold>');
    }

    function it_should_rebuild_state_string($filesystem, $resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];
        $filesystem->pathExists('/home/user/php/spec/File2Spec.php')->willReturn(true);
        $filesystem->pathExists('/home/user/php/spec/File3Spec.php')->willReturn(true);

        $this->buildState($resources)->shouldReturn('<fg=green;options=bold>3 classes</fg=green;options=bold>    <fg=green;options=bold>3 specs</fg=green;options=bold>');
    }

    function it_should_print_well_done($filesystem, $resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];
        $filesystem->pathExists('/home/user/php/spec/File2Spec.php')->willReturn(true);
        $filesystem->pathExists('/home/user/php/spec/File3Spec.php')->willReturn(true);

        $this->buildTrace($resources)->shouldReturn(['', '<fg=green>Well done !!!</fg=green>', '']);
    }

    function it_should_print_error_trace($resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];

        $this->buildTrace($resources)->shouldReturn([
            '',
            '<fg=red>No spec found for class <fg=red;options=bold>"Knp/File2"</fg=red;options=bold></fg=red>',
            '',
            '<fg=red>No spec found for class <fg=red;options=bold>"Knp/File3"</fg=red;options=bold></fg=red>',
            ''
        ]);
    }

    function it_should_return_code_1_when_all_classes_dont_have_spec($resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];

        $this->buildCode($resources)->shouldReturn(1);
    }

    function it_should_return_code_0_when_all_classes_have_spec($filesystem, $resource1, $resource2, $resource3)
    {
        $resources = [$resource1, $resource2, $resource3];
        $filesystem->pathExists('/home/user/php/spec/File2Spec.php')->willReturn(true);
        $filesystem->pathExists('/home/user/php/spec/File3Spec.php')->willReturn(true);

        $this->buildCode($resources)->shouldReturn(0);
    }
}
