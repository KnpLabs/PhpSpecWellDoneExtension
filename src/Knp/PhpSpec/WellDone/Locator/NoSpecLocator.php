<?php

namespace Knp\PhpSpec\WellDone\Locator;

use PhpSpec\Locator\PSR0\PSR0Locator;
use PhpSpec\Util\Filesystem;
use PhpSpec\Locator\PSR0\PSR0Resource;

class NoSpecLocator extends PSR0Locator
{
    public function __construct($srcNamespace = '', $specNamespacePrefix = 'spec',
                                $srcPath = 'src', $specPath = '.', Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?: new Filesystem;

        parent::__construct($srcNamespace, $specNamespacePrefix, $srcPath, $specPath, $filesystem);
    }

    public function getAllResources()
    {
        return $this->findNotSpecResources($this->getFullSrcPath());
    }

    protected function findNotSpecResources($path)
    {
        if (!$this->filesystem->pathExists($path)) {
            return array();
        }

        $resources = array();
        foreach ($this->filesystem->findPhpFilesIn($path) as $file) {
            $resource = $this->createResourceFromSpecFile($file->getRealPath());
            if (!$this->filesystem->pathExists($resource->getSpecFilename())) {
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    private function createResourceFromSpecFile($path)
    {
        // cut "Spec.php" from the end
        $p = $this->getFullSpecPath();

        $relative = substr($path, strlen($p), -4);
        $relative = preg_replace('/Spec$/', '', $relative);


        return new PSR0Resource(explode(DIRECTORY_SEPARATOR, $relative), $this);
    }
}
