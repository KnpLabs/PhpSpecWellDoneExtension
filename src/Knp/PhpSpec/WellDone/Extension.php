<?php

namespace Knp\PhpSpec\WellDone;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Util\Filesystem;
use PhpSpec\ServiceContainer;
use Symfony\Component\Console\Input\InputArgument;
use Knp\PhpSpec\WellDone\Locator\NoSpecLocator;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $this->setupConsole($container);
        $this->setupCommands($container);
        $this->setupLocators($container);
        $this->setupFormatter($container);
    }

    protected function setupConsole(ServiceContainer $container)
    {
        $definition = $container->get('console.commands.run')->getDefinition();
        $definition->addArgument(
            new InputArgument(
                'status',
                InputArgument::OPTIONAL,
                'Display global status of specifications'
            )
        );
    }

    protected function setupCommands(ServiceContainer $container)
    {
        $container->setShared('console.commands.status', function ($c) {
            return new Console\Command\StatusCommand(new Filesystem, $c->get('console.formater.well.progress'));
        });
    }

    protected function setupLocators(ServiceContainer $container)
    {
        $container->addConfigurator(function ($c) {
            $suites = $c->getParam('suites', array('main' => ''));

            foreach ($suites as $name => $suite) {

                $suite = $this->loadSuiteInformations($suite);
                $this->buildDirectories($suite);

                $c->set(sprintf('locator.locators.no_spec_%s_suite', $name),
                    function ($c) use ($suite) {
                        return new NoSpecLocator($suite['srcNS'], $suite['specPrefix'], $suite['srcPath'], $suite['specPath']);
                    }
                );
            }
        });
    }

    protected function setupFormatter(ServiceContainer $container)
    {
        $container->setShared('console.formater.well.progress', function () {
            return new Formater\ProgressFormater(new Filesystem);
        });
    }

    protected function loadSuiteInformations($suite)
    {
        $result               = [];
        $suite                = is_array($suite) ? $suite : array('namespace' => $suite);
        $result['srcNS']      = $suite['namespace'];
        $result['specPrefix'] = isset($suite['spec_prefix']) ? $suite['spec_prefix'] : 'spec';
        $result['srcPath']    = isset($suite['src_path']) ? $suite['src_path'] : 'src';
        $result['specPath']   = isset($suite['spec_path']) ? $suite['spec_path'] : '.';

        return $result;
    }

    protected function buildDirectories(array $suite)
    {
        if (!is_dir($suite['srcPath'])) {
            mkdir($suite['srcPath'], 0777, true);
        }
        if (!is_dir($suite['specPath'])) {
            mkdir($suite['specPath'], 0777, true);
        }
    }
}
