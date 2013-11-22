<?php

namespace Knp\PhpSpec\WellDone\Locator;

use PhpSpec\Locator\PSR0\PSR0Locator;
use PhpSpec\Locator\PSR0\PSR0Resource;
use Knp\PhpSpec\WellDone\Locator\ResourceInspector;

class NoSpecLocator extends PSR0Locator
{
    public function __construct(ResourceInspector $inspector, $srcNamespace = '', $specNamespacePrefix = 'spec', $srcPath = 'src', $specPath = '.')
    {
        $this->inspector = $inspector;

        parent::__construct($srcNamespace, $specNamespacePrefix, $srcPath, $specPath, $this->inspector->getFilesystem());
    }

    public function getAllResources()
    {
        return $this->findNotSpecResources($this->getFullSrcPath());
    }

    protected function findNotSpecResources($path)
    {
        if (!$this->getFilesystem()->pathExists($path)) {
            return array();
        }

        $resources = array();
        foreach ($this->getFilesystem()->findPhpFilesIn($path) as $file) {
            $resource = $this->createResourceFromSpecFile($file->getRealPath());
            if ($this->inspector->isClass($resource)
                && $this->inspector->isAbstract($resource)
                && !$this->inspector->hasSpec($resource)
            ) {
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    private function createResourceFromSpecFile($path)
    {
        $p = $this->getFullSpecPath();
        if ('/' === substr($p, -1)) {
            $p = substr($p, 0, -1);
        }

        $relative = substr($path, strlen($p), -4);
        $relative = preg_replace('/Spec$/', '', $relative);

        return new PSR0Resource(explode(DIRECTORY_SEPARATOR, $relative), $this);
    }

    protected function getFilesystem()
    {
        return $this->inspector->getFilesystem();
    }

}
