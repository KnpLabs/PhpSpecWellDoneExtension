<?php

namespace Knp\PhpSpec\WellDone\Console\Command;

use PhpSpec\Util\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\PhpSpec\WellDone\Formater\ProgressFormater;
use Symfony\Component\Console\Input\InputOption;

class StatusCommand extends Command
{
    protected $filesystem;
    protected $formater;

    public function __construct(Filesystem $filesystem, ProgressFormater $formater)
    {
        parent::__construct('status');

        $this->filesystem = $filesystem;
        $this->formater   = $formater;
    }

    public function configure()
    {
        $this
            ->setName('status')
            ->setDescription('Say which class has spec or not.')
            ->setDefinition(array(
                new InputOption('exclude', 'e', InputOption::VALUE_REQUIRED, 'File exclusion pattern (ex : "*Controller, App\Entity\*")', null)
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $container->configure();

        $exclusion = $container->getParam('knp.welldone.exclusion', '');
        if (null !== $input->getOption('exclude')) {
            $exclusion = $input->getOption('exclude');
        }

        if (is_array($exclusion)) {
            $exclusion = implode(', ', $exclusion);
        }

        $resources = $container->get('locator.resource_manager')->locateResourcesWithExclusion($exclusion);

        foreach ($this->buildMessages($resources) as $message) {
            $output->writeln($message);
        }

        return $this->formater->buildCode($resources);
    }

    protected function buildMessages(array $resources)
    {
        return array_merge(
            [ '', $this->formater->buildProgressBar($resources) ],
            [ '', $this->formater->buildState($resources) ],
            $this->formater->buildTrace($resources)
        );
    }
}
