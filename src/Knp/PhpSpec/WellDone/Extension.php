<?php

namespace Knp\PhpSpec\WellDone;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $this->setupConsole($container);
        $this->setupCommands($container);
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
}
