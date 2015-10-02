<?php

namespace Knp\PhpSpec\WellDone\Util;

use PhpSpec\Util\Filesystem as BaseFilesystem;

class Filesystem extends BaseFilesystem
{
    public function getTokens($path)
    {
        if ( ! $this->pathExists($path)) {
            return;
        }

        return token_get_all(
            $this->getFileContents($path)
        );
    }
}
