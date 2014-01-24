<?php

namespace Knp\PhpSpec\WellDone\Locator;

interface ExclusionLocatorInterface
{
    public function findResourcesWithExclusion($query);
    public function supportsExclusionQuery($query);
}
