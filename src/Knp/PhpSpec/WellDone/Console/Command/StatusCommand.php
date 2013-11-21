<?php

namespace Knp\PhpSpec\WellDone\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
    public function __construct()
    {
        parent::__construct('status');

        $this->setDefinition(array());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
