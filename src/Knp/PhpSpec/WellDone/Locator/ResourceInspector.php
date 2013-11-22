<?php

namespace Knp\PhpSpec\WellDone\Locator;

use PhpSpec\Util\Filesystem;
use PhpSpec\Locator\PSR0\PSR0Resource;

class ResourceInspector
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }

    public function hasSpec(PSR0Resource $resource)
    {
        return $this->filesystem->pathExists($resource->getSpecFilename());
    }

    public function isClass(PSR0Resource $resource)
    {
        return $this->is($resource, T_CLASS);
    }

    public function isAbstract(PSR0Resource $resource)
    {
        return $this->is($resource, T_ABSTRACT);
    }

    protected function is(PSR0Resource $resource, $tag)
    {
        $tokens = $this->filesystem->getTokens($resource->getSrcFilename());

        foreach ($tokens as $token) {
            if (is_array($token) && current($token) === $tag) {
                return true;
            }
        }

        return false;
    }
}