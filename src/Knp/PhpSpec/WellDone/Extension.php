<?php

namespace Knp\PhpSpec\WellDone;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Knp\PhpSpec\WellDone\Locator\NoSpecLocator;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $this->setupConsole($container);
        $this->setupCommands($container);
        $this->setupLocators($container);
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
        $container->setShared('console.commands.status', function($c) {
            return new Console\Command\StatusCommand;
        });
    }

    protected function setupLocators(ServiceContainer $container)
    {
        $container->addConfigurator(function($c) {
            $suites = $c->getParam('suites', array('main' => ''));

            foreach ($suites as $name => $suite) {
                $suite      = is_array($suite) ? $suite : array('namespace' => $suite);
                $srcNS      = $suite['namespace'];
                $specPrefix = isset($suite['spec_prefix']) ? $suite['spec_prefix'] : 'spec';
                $srcPath    = isset($suite['src_path']) ? $suite['src_path'] : 'src';
                $specPath   = isset($suite['spec_path']) ? $suite['spec_path'] : '.';

                if (!is_dir($srcPath)) {
                    mkdir($srcPath, 0777, true);
                }
                if (!is_dir($specPath)) {
                    mkdir($specPath, 0777, true);
                }

                $c->set(sprintf('locator.locators.no_spec_%s_suite', $name),
                    function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                        return new NoSpecLocator($srcNS, $specPrefix, $srcPath, $specPath);
                    }
                );
            }
        });
    }
}
