<?php

namespace Knp\PhpSpec\WellDone\Formater;

use PhpSpec\Util\Filesystem;
use PhpSpec\Locator\PSR0\PSR0Resource;

class ProgressFormater
{
    public function __construct(Filesystem $filesystem, $max = 50)
    {
        $this->filesystem = $filesystem;
        $this->max        = $max;
    }

    public function buildProgressBar(array $resources)
    {
        $done    = $this->filter($resources, true);
        $notDone = $this->filter($resources, false);

        $length = count($done) + count($notDone);
        $state = (count($done) * $this->max) / $length;

        $progress = sprintf(
            '<%s>%s</%s>',
            'fg=black;bg=green',
            str_pad(count($done), round($state), " ", STR_PAD_BOTH)
            , 'fg=black;bg=green'
        );

        if (0 < count($notDone)) {
            $progress .= sprintf(
                '<%s>%s</%s>',
                'error',
                str_pad(count($notDone), round($this->max - $state), " ", STR_PAD_BOTH),
                'error'
            );
        }

        return $progress;
    }

    public function buildState(array $resources, $delim = '    ')
    {
        $done    = $this->filter($resources, true);
        $notDone = $this->filter($resources, false);

        $state  = '';
        $state .= sprintf('<fg=green;options=bold>%s class%s</fg=green;options=bold>', count($done) + count($notDone), 1 < count($done) + count($notDone) ? 'es' : '');
        $state .= $delim;
        $state.= sprintf('<fg=green;options=bold>%s spec%s</fg=green;options=bold>', count($done), 1 < count($done) ? 's' : '');
        if (0 !== count($notDone)) {
            $state .= $delim;
            $state .= sprintf('<fg=red;options=bold>%s missing%s</fg=red;options=bold>', count($notDone), 1 < count($notDone) ? 's' : '');
        }

        return $state;
    }

    public function buildTrace(array $resources)
    {
        $result = [''];

        if (0 === count($notDone = $this->filter($resources, false))) {
            $result[] = '<fg=green>Well done !!!</fg=green>';
            $result[] = '';
        } else {
            foreach ($notDone as $resource) {
                $class = str_replace('\\', '/', $resource->getSrcClassname());
                $message = sprintf('No spec found for class <fg=red;options=bold>"%s"</fg=red;options=bold>', $class);
                $result[] = sprintf('<fg=red>%s</fg=red>', $message);
                $result[] = "";
            }
        }

        return $result;
    }

    public function buildCode($resources)
    {
        $notDone = $this->filter($resources, false);

        return 0 === count($notDone) ? 0 : 1;
    }

    protected function filter(array $resources, $withSpec = true)
    {
        $result = [];

        foreach ($resources as $resource) {
            if ($withSpec === $this->hasSpec($resource)) {
                $result[] = $resource;
            }
        }

        return $result;
    }

    protected function hasSpec(PSR0Resource $resource)
    {
        return $this
            ->filesystem
            ->pathExists($resource->getSpecFilename())
        ;
    }
}
