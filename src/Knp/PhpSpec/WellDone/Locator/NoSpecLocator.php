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
            if ($this->isClassFile($resource) && !$this->asSpecFile($resource)) {
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

    private function isClassFile(PSR0Resource $resource)
    {
        $tokens = token_get_all(file_get_contents($resource->getSrcFilename()));

        foreach ($tokens as $token) {
            if (is_array($token) && current($token) === T_CLASS) {
                return true;
            }
        }

        return false;
    }

    private function asSpecFile(PSR0Resource $resource)
    {
        return $this->filesystem->pathExists($resource->getSpecFilename());
    }
}
